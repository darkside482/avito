#!/bin/sh
set -e

if [ X$XDEBUG_HOST != X ]; then
    sudo -i sed -i "s/xdebug\\.remote_host=.*/xdebug.remote_host=$XDEBUG_HOST/g" /etc/php/7.4/mods-available/xdebug.ini
fi

if [ X$XDEBUG_PORT != X ]; then
    sudo -i sed -i "s/xdebug\\.remote_port=.*/xdebug.remote_port=$XDEBUG_PORT/g" /etc/php/7.4/mods-available/xdebug.ini
fi

if [ X$XDEBUG_IDE_KEY != X ]; then
    sudo -i sed -i "s/xdebug\\.idekey=.*/xdebug.idekey=$XDEBUG_IDE_KEY/g" /etc/php/7.4/mods-available/xdebug.ini
fi

sudo -i exec "$@" --allow-to-run-as-root