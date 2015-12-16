#!/bin/bash

# Uncomment to debug the script
#set -x

# Do not change this variable !!
SCRIPT_DIR=$(cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )

# Calculate the base directory based on the location of the script.
BASEDIR=$(realpath $SCRIPT_DIR/../../..)

# You can override the base directory here if you whish
#BASEDIR=../../Tests

# When BREAK_ON_ERROR is set to 0, a failing test suite will not stop the tests.
# Set it to 1 to stop on the first failing test suite.
BREAK_ON_ERROR=1

# The reg-exp used to search for test suites, should probably not be changed
FILES_REGEXP=".*\/Packages\/Application\/.*Tests\/JavaScript\/Functional\/.*Test.js"

# Casper executable
CASPER_BIN=casperjs

# Additional casper options
#CASPER_OPTIONS="--concise"
CASPER_OPTIONS=

# ----- There should be nothing to change below

FILES=$(find $BASEDIR -type f -regex "$FILES_REGEXP" | sort -V)

for FILE in $FILES; do

  $CASPER_BIN test $CASPER_OPTIONS \
        --includes=$SCRIPT_DIR/lib/bootstrap.js \
        --basedir=$BASEDIR \
        --libdir=$SCRIPT_DIR/lib \
        --post=$SCRIPT_DIR/lib/post.js \
        $FILE

  if [[ $BREAK_ON_ERROR != 0 && $? != 0 ]]; then
    break;
  fi

done
