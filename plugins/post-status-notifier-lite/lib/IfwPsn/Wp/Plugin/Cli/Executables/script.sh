#!/bin/sh
# echo $@
# exit

TARGET_FILE=$0
cd `dirname $TARGET_FILE`
# LOC_DIR=`pwd`
PHYS_DIR=`pwd -P`
cd $PHYS_DIR

for i in "$@"
do
case $i in
    -php=*|--php=*)
    PHP="${i#*=}"
    ;;
esac
done

if [ "${PHP}" != "" ]; then
    CMD="${PHP} -f script.php -- $@"
    eval ${CMD}
elif command -v /usr/local/bin/php >/dev/null 2>&1; then
    /usr/local/bin/php -f script.php -- $@
else
    php -f script.php -- $@
fi