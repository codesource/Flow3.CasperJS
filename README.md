# Flow3.CasperJS
CasperJS loader for javascript functional testing on Flow3 architecture

## Installation

To install this package, simply add a dependency on cdsrc/flow-casperjs to your project's composer.json file.
```
{
    "repositories": [
        {
            "type": "git",
            "url": "https://github.com/codesource/Flow3.CasperJS.git"
        }
    ],
    "require":{
        "cdsrc/flow-casperjs": "dev-master",  
    },
    "scripts": {
        "post-update-cmd": [
            "CDSRC\\Flow\\CasperJS\\Installer\\Installer::install"
        ],
        "post-install-cmd": [
            "CDSRC\\Flow\\CasperJS\\Installer\\Installer::install"
        ],
    }
}
```

## Running the tests

Running ```bin/cdsrc-flow-casperjs``` without arguments will use the regular expression in the configuration to search for all
the test files matching.

If you specify parameters, they will be used to match specific files or directories.

**Be warned that in any case the file list will be filtered with the regular expression in the configuration.**

#### Examples

```bash
# Run all tests for all packages
bin/cdsrc-flow-casperjs

# Run all tests in a directory
bin/cdsrc-flow-casperjs \
    Packages/Application/My.Package/Tests/JavaScript/Functional/MyTestDir

# Run a specific test file
bin/cdsrc-flow-casperjs \
    Packages/Application/My.Package/Tests/JavaScript/Functional/MyTest.js

# Matching a group of files with bash globing
bin/cdsrc-flow-casperjs \ 
    Packages/Application/My.Package/Tests/JavaScript/Functional/*[0-9]Test.js

# Multiple parameters
bin/cdsrc-flow-casperjs \
    Packages/Application/My.Package/Tests/JavaScript/Functional/MyTestDir \
    Packages/Application/My.Package/Tests/JavaScript/Functional/MyTest.js

# Get help and options
bin/cdsrc-flow-casperjs --help
```

## PhantomJs 1.9.8 warning

A bug in PhantomJs 1.9.8 will cause the following error at the end of the tests:

```
Unsafe JavaScript attempt to access frame with URL about:blank from frame with URL
file:///somepath/node_modules/casperjs/bin/bootstrap.js. Domains, protocols and ports must match.
```

This is a problem related to PhantomJs and does not indicate there is an issue with this script or your tests.

More information can be found here: https://github.com/ariya/phantomjs/issues/12697
