#!/bin/bash

# Enable IP forwarding
sudo sysctl -w net.ipv4.ip_forward=1
sudo bash -c 'echo "net.ipv4.ip_forward=1" >> /etc/sysctl.conf'

# Set up NAT between wlan0 (AP) and eth0 (internet)
sudo iptables -t nat -A POSTROUTING -o eth0 -j MASQUERADE
sudo iptables -A FORWARD -i eth0 -o wlan0 -m state --state RELATED,ESTABLISHED -j ACCEPT
sudo iptables -A FORWARD -i wlan0 -o eth0 -j ACCEPT

# Save iptables rules
sudo sh -c 'iptables-save > /etc/iptables/rules.v4'

echo "✅ External access (internet sharing) enabled via eth0 -> wlan0"
