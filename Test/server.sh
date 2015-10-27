#!/usr/bin/env bash

# STunnel uses OpenSSL, which requires super user privileges.
# Do the following to allow this script to execute:
#
# In your console, enter the following command:
# bash$ sudo visudo
#
# Add the following line to the sudoers file:
#
# root ALL=(ALL) NOPASSWD: [enter_absolute_path_to_project]/Test/server.sh
#
# Now the test suite will be able to run this script

TUNNEL_ID=`ps aux | grep [s]tunnel | awk '{print $2}'`;

if [ ${TUNNEL_ID:-0} -eq 0 ]
then
    stunnel secure/stunnel.conf > /dev/null;
    echo "$TUNNEL_ID";
else
    echo "Killing PID $TUNNEL_ID";
    kill "$TUNNEL_ID";
fi