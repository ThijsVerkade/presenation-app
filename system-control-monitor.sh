#!/bin/bash

# Create the script directory
mkdir -p /usr/local/bin

# Create the monitoring script
cat > /usr/local/bin/system-control-monitor.sh << 'EOF'
#!/bin/bash

echo "System control monitor started"

while true; do
    # Check for shutdown request
    if [ -f /tmp/shutdown-requested ]; then
        echo "Shutdown requested by web interface"
        rm -f /tmp/shutdown-requested
        sleep 2
        shutdown -h now
        break
    fi

    # Check for restart request
    if [ -f /tmp/restart-requested ]; then
        echo "Restart requested by web interface"
        rm -f /tmp/restart-requested
        sleep 2
        reboot
        break
    fi

    sleep 1
done
EOF

chmod +x /usr/local/bin/system-control-monitor.sh
