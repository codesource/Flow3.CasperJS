#!/bin/bash

# Uncomment to debug the script
#set -x

# Do not change this variable !!
SCRIPT_DIR=$(cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )

# Calculate the base directory based on the location of the script.
BASEDIR=$(realpath $SCRIPT_DIR/../../..)

source $SCRIPT_DIR/config.sh

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
