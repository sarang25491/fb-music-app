#!/bin/bash
# This is a simple script that compiles the plugin using the free Flex SDK on Linux/Mac.
# Learn more at http://developer.longtailvideo.com/trac/wiki/PluginsCompiling

FLEXPATH=/Developer/flex_sdk/


echo "Compiling positioning plugin..."
$FLEXPATH/bin/mxmlc ./burstplugin.as -sp ./ -o ./burstplugin.swf -library-path+=./lib -load-externs ./lib/jwplayer-5-classes.xml -use-network=false
