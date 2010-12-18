#!/bin/bash
# This is a simple script that compiles the plugin using the free Flex SDK on Linux/Mac.
# Learn more at http://developer.longtailvideo.com/trac/wiki/PluginsCompiling

JW_API_PATH=../fl5-plugin-sdk
FLEXPATH=../flex-sdk


echo "Compiling positioning plugin..."
$FLEXPATH/bin/mxmlc ./burstplugin.as -sp ./ -o ../burstplugin.swf -library-path+=$JW_API_PATH/lib -load-externs $JW_API_PATH/lib/jwplayer-5-classes.xml -use-network=false
