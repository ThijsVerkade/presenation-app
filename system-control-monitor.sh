#!/bin/bash

echo "System control monitor started"

# Create the control directory if it doesn't exist
mkdir -p /tmp/presentation-control

while true; do
    # Check for shutdown request
    if [ -f /tmp/presentation-control/shutdown-requested ]; then
        echo "Shutdown requested by web interface"
        rm -f /tmp/presentation-control/shutdown-requested
        sleep 2
        shutdown -h now
        break
    fi

    # Check for restart request
    if [ -f /tmp/presentation-control/restart-requested ]; then
        echo "Restart requested by web interface"
        rm -f /tmp/presentation-control/restart-requested
        sleep 2
        reboot
        break
    fi

    sleep 1
done
