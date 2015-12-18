# Flow3.CasperJS
CasperJS loader for javascript functional testing on Flow3 architecture

## Installation

The files must be installed in the Flow3 architecture, under "Build/BuildEssentials/Casper"

Node.js is required to install the dependencies.

Depending on your installation you might have to create symlinks to some binaries (i.e. phantomjs).

### Install the dependencies

```
cd Build/BuildEssentials/Casper
npm install
```

### Setup the configuration

Copy the ```config.sh.dist``` file to ```config.sh```, edit it to your needs (see below) and then make it an executable file:

```
chmod +x config.sh
```

Copy the ```options.js.dist``` file to ```options.js``` and edit it to your needs.

### Setup the runner script

Edit config.sh and adapt the values to fit your configuration.

Typically you might want to modify the following variables:

 * ```CASPER_BIN``` - Location of the ```casperjs``` binary
 * ```CASPER_OPTIONS``` - Additional CasperJs options

By default the runner will look for tests in ```<Package>/Tests/JavaScript/Functional/**/*Test.js```.

This can be changed modifying the ```FILES_REGEXP``` variable.

## Running the tests

Running ```run-test.sh``` without arguments will use the regular expression in the configuration to search for all
the test files matching.

If you specify parameters, they will be used to match specific files or directories.

**Be warned that in any case the file list will be filtered with the regular expression in the configuration.**

#### Examples

```bash
# Run all tests for all packages
Build/BuildEssentials/Casper/run-tests.sh

# Run all tests in a directory
Build/BuildEssentials/Casper/run-tests.sh \
    Packages/Application/My.Package/Tests/JavaScript/Functional/MyTestDir

# Run a specific test file
Build/BuildEssentials/Casper/run-tests.sh \
    Packages/Application/My.Package/Tests/JavaScript/Functional/MyTest.js

# Matching a group of files with bash globing
Build/BuildEssentials/Casper/run-tests.sh \ 
    Packages/Application/My.Package/Tests/JavaScript/Functional/*[0-9]Test.js

# Multiple parameters
Build/BuildEssentials/Casper/run-tests.sh \
    Packages/Application/My.Package/Tests/JavaScript/Functional/MyTestDir \
    Packages/Application/My.Package/Tests/JavaScript/Functional/MyTest.js

```

## PhantomJs 1.9.8 warning

A bug in PhantomJs 1.9.8 will cause the following error at the end of the tests:

```
Unsafe JavaScript attempt to access frame with URL about:blank from frame with URL
file:///somepath/node_modules/casperjs/bin/bootstrap.js. Domains, protocols and ports must match.
```

This is a problem related to PhantomJs and does not indicate there is an issue with this script or your tests.

More information can be found here: https://github.com/ariya/phantomjs/issues/12697
