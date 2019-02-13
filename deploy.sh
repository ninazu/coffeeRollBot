#!/bin/sh

DIR=$(dirname $(readlink -f "${0}"))

cd ${DIR}
git pull
git submodule foreach git pull origin master


