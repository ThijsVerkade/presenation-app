#!/bin/bash

set -e

# Configuration (can be adjusted)
SSID="PresentationNetwork"
PASSWORD="YourStrongPassword"
STATIC_IP="192.168.4.1"
DHCP_RANGE_START="192.168.4.10"
DHCP_RANGE_END="192.168.4.100"

# Install required packages
sudo apt update
sudo apt install -y hostapd dnsmasq netfilter-persistent iptables-persistent

# Enable services
sudo systemctl unmask hostapd
sudo systemctl enable hostapd
sudo systemctl enable dnsmasq

# Configure static IP for wlan0
sudo bash -c "cat >> /etc/dhcpcd.conf <<EOF
interface wlan0
    static ip_address=${STATIC_IP}/24
    nohook wpa_supplicant
EOF"

# Backup and configure dnsmasq
sudo mv /etc/dnsmasq.conf /etc/dnsmasq.conf.orig || true
sudo bash -c "cat > /etc/dnsmasq.conf <<EOF
interface=wlan0
dhcp-range=${DHCP_RANGE_START},${DHCP_RANGE_END},255.255.255.0,24h
EOF"

# Configure hostapd
sudo bash -c "cat > /etc/hostapd/hostapd.conf <<EOF
interface=wlan0
driver=nl80211
ssid=${SSID}
hw_mode=g
channel=7
wmm_enabled=0
macaddr_acl=0
auth_algs=1
ignore_broadcast_ssid=0
wpa=2
wpa_passphrase=${PASSWORD}
wpa_key_mgmt=WPA-PSK
rsn_pairwise=CCMP
EOF"

# Point to the hostapd config file
sudo sed -i 's|#DAEMON_CONF=\"\"|DAEMON_CONF=\"/etc/hostapd/hostapd.conf\"|' /etc/default/hostapd

# Restart services
sudo systemctl restart dhcpcd
sudo systemctl start hostapd
sudo systemctl start dnsmasq

echo "✅ Access point setup complete. Connect to '${SSID}' with IP ${STATIC_IP}"
