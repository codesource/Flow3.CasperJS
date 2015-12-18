<?php

namespace FlowCasperJS;

class System
{

    /**
     * Sub context
     */
    const SUB_CONTEXT = 'CasperJS';

    /**
     * Allowed main contexts
     *
     * @var array
     */
    protected static $CONTEXTS = array('Development', 'Testing', 'Production');

    /**
     * @var string
     */
    protected $context;

    /**
     * @var string
     */
    protected $rootPath;

    /**
     * @var string
     */
    protected $webPath;

    /**
     * System constructor.
     */
    public function __construct()
    {
        $mainContext = isset($_ENV['FLOW_CONTEXT']) && in_array($_ENV['FLOW_CONTEXT'], self::$CONTEXTS) ?
            $_ENV['FLOW_CONTEXT'] :
            self::$CONTEXTS[0];
        $this->context = $mainContext . '/' . self::SUB_CONTEXT;
        $this->rootPath = realpath(rtrim(__DIR__, '/') . '/../../..');
        $this->webPath = $this->rootPath . '/Web';

        $this->updateServerSettings();
    }

    /**
     * @return string
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @return string
     */
    public function getRootPath()
    {
        return $this->rootPath;
    }

    /**
     * @return string
     */
    public function getWebPath()
    {
        return $this->webPath;
    }

    /**
     * Update global SERVER array with rewriting data
     */
    protected function updateServerSettings()
    {
        $_SERVER['REDIRECT_FLOW_CONTEXT'] = $this->context;
        $_SERVER['REDIRECT_FLOW_REWRITEURLS'] = 1;
        $_SERVER['REDIRECT_STATUS'] = 200;

        $_SERVER['FLOW_CONTEXT'] = $this->context;
        $_SERVER['FLOW_REWRITEURLS'] = 1;

        $_SERVER['DOCUMENT_ROOT'] = $this->webPath;

        $_SERVER['CONTEXT_PREFIX'] = '';
        $_SERVER['CONTEXT_DOCUMENT_ROOT'] = $this->webPath;

        $_SERVER['REDIRECT_URL'] = $_SERVER['REQUEST_URI'];
        $_SERVER['QUERY_STRING'] = isset($_SERVER['QUERY_STRING']) ?: '';
        $_SERVER['REQUEST_SCHEME'] = 'http';

    }


}

class MimeTypeResolver
{
    /**
     * @var array
     */
    protected $mimeTypes = array();

    /**
     * MimeTypeResolver constructor.
     */
    public function __construct()
    {
        $this->buildMimeTypesFromSystem();
    }

    /**
     * Get filename extension
     *
     * @param $filename
     *
     * @return string
     */
    public function getExtension($filename)
    {
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (!$ext) {
            $ext = $filename;
        }

        return strtolower($ext);
    }

    /**
     * Get mime-type for given filename
     *
     * @param $filename
     *
     * @return null|string
     */
    public function getMimeType($filename)
    {
        $ext = $this->getExtension($filename);

        return isset($this->mimeTypes[$ext]) ? $this->mimeTypes[$ext] : null;
    }

    /**
     * Read all mime-types from system
     */
    protected function buildMimeTypesFromSystem()
    {
        $this->mimeTypes = array();
        $file = fopen('/etc/mime.types', 'r');
        while (($line = fgets($file)) !== false) {
            $line = trim(preg_replace('/#.*/', '', $line));
            if (!$line)
                continue;
            $parts = preg_split('/\s+/', $line);
            if (count($parts) == 1)
                continue;
            $type = array_shift($parts);
            foreach ($parts as $part)
                $this->mimeTypes[$part] = $type;
        }
        fclose($file);
    }
}

class Router
{

    /**
     * @var \FlowCasperJS\MimeTypeResolver
     */
    protected $mimeTimeResolver;

    /**
     * @var \FlowCasperJS\System
     */
    protected $system;

    /**
     * Router constructor.
     */
    public function __construct()
    {
        $this->mimeTimeResolver = new MimeTypeResolver();
        $this->system = new System();
    }

    /**
     * Route the request
     *
     * @return bool
     */
    public function route()
    {
        $URI = ltrim($_SERVER['REQUEST_URI'], '/');
        $webPath = $this->system->getWebPath();
        $fullUriPath = $webPath . '/' . $URI;

        # Stop rewrite processing no matter if a package resource, robots.txt etc. exists or not
        if (preg_match(':^(_Resources/Packages/|robots\.txt|favicon\.ico):', $URI)) {
            $this->sendFile($fullUriPath);
        }

        # Stop rewrite process if the path points to a static file anyway
        if ($URI !== '' && file_exists($fullUriPath)) {
            $this->sendFile($fullUriPath);
        }

        # Perform rewriting of persistent private resources
        if (preg_match(':^(_Resources/Persistent/[a-zA-Z0-9]+/(.+/)?[a-f0-9]{40})/.+(\..+):', $URI, $matches)) {
            $filename = $webPath . '/' . $matches[1] . $matches[3];
            $this->sendFile($filename);
        }

        # Perform rewriting of persistent resource files
        if (preg_match(':^(_Resources/Persistent/.{40})/.+(\..+):', $URI, $matches)) {
            $filename = $webPath . '/' . $matches[1] . $matches[2];
            $this->sendFile($filename);
        }

        # Make sure that not existing resources don't execute TYPO3 Flow
        if (preg_match(':^_Resources/.*:', $URI)) {
            $this->sendFile($fullUriPath);
        }

        # Continue only if the file/symlink/directory does not exist
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['SCRIPT_FILENAME'] = $webPath . '/index.php';
        $_SERVER['PHP_SELF'] = '/index.php';
        include $webPath . '/' . 'index.php';

        return true;
    }

    /**
     * Send existing file to client with the right mime-type
     *
     * @param $filename
     */
    protected function sendFile($filename)
    {
        if (is_file($filename)) {
            $mimeType = $this->mimeTimeResolver->getMimeType($filename);
            if ($mimeType) {
                header('Content-Type: ' . $mimeType);
            }
            if (in_array($this->mimeTimeResolver->getExtension($filename), array('css', 'js', 'jpg', 'png', 'gif'))) {
                $offset = 86400 * 7;
                $mtime = filemtime($filename);
                $eTag = sprintf('%08x-%08x', crc32($filename), $mtime);
                $gmt_mtime = gmdate('D, d M Y H:i:s', $mtime) . ' GMT';
                if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && !empty($_SERVER['HTTP_IF_NONE_MATCH'])) {
                    $tmp = explode(';', $_SERVER['HTTP_IF_NONE_MATCH']); // IE fix!
                    if (!empty($tmp[0]) && strtotime($tmp[0]) == strtotime($gmt_mtime)) {
                        header('HTTP/1.1 304 Not Modified');
                        exit;
                    }
                }
                if (isset($_SERVER['HTTP_IF_NONE_MATCH'])) {
                    if (str_replace(array('\"', '"'), '', $_SERVER['HTTP_IF_NONE_MATCH']) == $eTag) {
                        header('HTTP/1.1 304 Not Modified');
                        exit;
                    }
                }
                header('Age: 0');
                header('Date: ' . gmdate("D, d M Y H:i:s") . " GMT");
                header('Expires: ' . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT");
                header("Last-Modified: " . $gmt_mtime);
                header('Cache-Control: public, max-age=' . $offset);
                header('ETag: "' . $eTag . '"');
            } else {
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Pragma: public');
            }
            header('Content-Length: ' . filesize($filename));
            ob_clean();
            flush();
            readfile($filename);
            exit;
        } else {
            header("HTTP/1.0 404 Not Found");
            exit;
        }
    }
}


$router = new Router();

return $router->route();

