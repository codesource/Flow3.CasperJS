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

### Running the tests

Running ```run-test.sh``` without arguments will use the regular expression in the configuration to search for all
the test files matching.

If you specify parameters, they will be used to match specific files or directories.

Be warned that in any case the files list will be filtered by the regular expression in the configuration.

Examples:

```bash
# Run all tests for all packages
Build/BuildEssentials/Casper/run-tests.sh

# Run all tests in a directory
Build/BuildEssentials/Casper/run-tests.sh Packages/Application/My.Package/Tests/JavaScript/Functional/

# Run a specific test file
Build/BuildEssentials/Casper/run-tests.sh Packages/Application/My.Package/Tests/JavaScript/Functional/MyTest.js

# Matching a group of files
Build/BuildEssentials/Casper/run-tests.sh Packages/Application/My.Package/Tests/JavaScript/Functional/*[0-9]Test.js

```
