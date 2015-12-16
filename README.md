# Flow3.CasperJS
CasperJS loader for javascript functional testing on Flow3 architecture

## Installation

The files must be install into Flow3 architecture, under "Build/BuildEssentials/Casper"

Node.js is required to build and run the tests.

Depending on your installation you might have to create symlinks to some binaries (i.e. phantomjs).

### Install the dependencies

```
cd Build/BuildEssentials/Casper
npm install
```

### Setup the configuration

Copy the ```options.js.dist``` file to ```options.js``` and edit it to your needs.

### Setup the runner script

Edit run-tests.sh and adapt the values to fit your configuration.

Typically you might want to modify the following variables:

 * ```CASPER_BIN``` - Location of the ```casperjs``` binary
 * ```CASPER_OPTIONS``` - Additional CasperJs options
 * ```BREAK_ON_ERROR``` - If set to 1 the runner will stop after a failing test

By default the runner will look for tests in ```<Package>/Tests/JavaScript/Functional/**/*Test.js```.

This can be changed modifying the ```FILES_REGEXP``` variable.
