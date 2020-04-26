#!/usr/bin/env bash

#--------------------------------------------------#
# This script compiles asset files.                #
#                                                  #
# Author: Jack Cherng <jfcherng@gmail.com>         #
#--------------------------------------------------#

SCRIPT_DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
THREAD_CNT=$(getconf _NPROCESSORS_ONLN)
PROJECT_ROOT=${SCRIPT_DIR}

LESS_FILES=(
    "skins/classic/main.less"
    "skins/elastic/main.less"
    "skins/larry/main.less"
)

JS_FILES=(
    "js/main.js"
)

PATH=${PROJECT_ROOT}/node_modules/.bin:${PATH}


#-------#
# begin #
#-------#

pushd "${SCRIPT_DIR}" || exit


#--------------------#
# compile LESS files #
#--------------------#

for file_src in "${LESS_FILES[@]}"; do
    if [ ! -f "${file_src}" ]; then
        echo "'${file_src}' is not a file..."
        continue
    fi

    echo "==================================="
    echo "Begin compile '${file_src}'..."
    echo "==================================="

    file_dst=${file_src%.*}.css

    lessc --insecure "${file_src}" \
        | printf "%s\n" "$(cat -)" \
        | cleancss -O2 -f 'breaks:afterAtRule=on,afterBlockBegins=on,afterBlockEnds=on,afterComment=on,afterProperty=on,afterRuleBegins=on,afterRuleEnds=on,beforeBlockEnds=on,betweenSelectors=on;spaces:aroundSelectorRelation=on,beforeBlockBegins=on,beforeValue=on;indentBy:2;indentWith:space;breakWith:lf' \
        > "${file_dst}"
done


#----------------------------#
# transpile Javascript files #
#----------------------------#

for file_src in "${JS_FILES[@]}"; do
    if [ ! -f "${file_src}" ]; then
        echo "'${file_src}' is not a file..."
        continue
    fi

    echo "==================================="
    echo "Begin transpile '${file_src}'..."
    echo "==================================="

    file_export=${file_src%.*}.export.js
    file_dst=${file_src%.*}.min.js

    if [ ! -f "${file_export}" ]; then
        has_no_file_export=true
        touch "${file_export}"
    fi

    # to make the output file more diff-friendly, we beautify it and remove leading spaces
    cat "${file_src}" "${file_export}" \
        | browserify -t [ babelify ] - \
        | terser --config-file terser.json -- \
        | sed -e 's/[[:space:]]+$//' \
        > "${file_dst}"

    if [ "${has_no_file_export}" = "true" ]; then
        rm -f "${file_export}"
    fi
done


#-----#
# end #
#-----#

popd || exit
