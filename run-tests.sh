#!/bin/bash

# Uncomment to debug the script
#set -x

# Do not change this variable !!
SCRIPT_DIR=$(cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )

# Calculate the base directory based on the location of the script.
BASEDIR=$(realpath $SCRIPT_DIR/../../..)

source $SCRIPT_DIR/config.sh

# ----- There should be nothing to change below

if [ -z "$1" ]; then
    FILES=$(find $BASEDIR -type f -regex "$FILES_REGEXP" | sort -V)
else
    # Read the files list from the parameters, expand the wildcards and filter them with the $FILES_REGEXP
    TMP_FILES=($@)
    TMP_FILES="${TMP_FILES[@]}"
    for FILE in $TMP_FILES; do
        FILES="$FILES $(find $FILE -type f -regex "$FILES_REGEXP" | sort -V)"
    done
fi

$CASPER_BIN test $CASPER_OPTIONS \
    --basedir=$BASEDIR \
    --libdir=$SCRIPT_DIR/lib \
    --pre=$SCRIPT_DIR/lib/bootstrap.js \
    --post=$SCRIPT_DIR/lib/post.js \
    $FILES
