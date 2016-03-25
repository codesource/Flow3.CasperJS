<?php
/**
 * @copyright Copyright (c) 2016 Code-Source
 */
namespace CDSRC\Flow\CasperJS\Installer;

use Composer\Script\Event;
use PhantomInstaller\Installer as InstallerPhantomJS;

class Installer
{
    /**
     * Post install/update script
     *
     * @param \Composer\Script\Event $event
     */
    public static function install(Event $event)
    {
        InstallerPhantomJS::installPhantomJS($event);
        self::installCasperJs($event);
    }

    /**
     * Install neuralys casperjs in bin directory
     *
     * @param \Composer\Script\Event $event
     */
    protected static function installCasperJs(Event $event)
    {
        $binDir = $event->getComposer()->getConfig()->get('bin-dir');
        $suffix = strpos(strtolower(php_uname()), "win") !== false ? '.exe' : '';
        symlink(__DIR__ . '/../../../../neuralys/casperjs/bin/casperjs' . $suffix, $binDir . '/casperjs' . $suffix);
    }
}