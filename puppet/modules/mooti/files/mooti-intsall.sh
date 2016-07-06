#!/bin/bash

#exit on first error
set -e

#DIR=$(/usr/bin/dirname $0)
#ROOT_DIR=$(/bin/bash -c "cd $DIR/..; /bin/pwd")

#cd $ROOT_DIR

function showUsage {
    echo "Usage: $0 [version]"
}

function installPlatform {
    echo "Installing $VERSION"
    cd /opt/mooti/    
    wget https://github.com/mooti/mooti-platform-admin/releases/download/$VERSION/mooti-platform-admin-$VERSION.zip
    rm -fr mooti-platform-admin
    unzip /opt/mooti/mooti-platform-admin-$VERSION.zip
    rm -f  /opt/mooti/mooti-platform-admin-$VERSION.zip    
}

if [[ "$#" -ne 1 ]]; then
    showUsage
    exit 1
fi

VERSION=$1

if [ -f /opt/mooti/mooti-platform-admin/version.txt ];
then
    CURRENT_VERSION=$(cat /opt/mooti/mooti-platform-admin/version.txt)
    echo "Current version is $CURRENT_VERSION"
    
    if [[ $(php -r "echo version_compare('$CURRENT_VERSION', '$VERSION');") -eq 0 ]]
    then
        echo "$VERSION already installed"
        exit 0
    fi
fi

installPlatform