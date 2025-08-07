#!/bin/bash
set -e

# Configuration
SSID="PresentationNetwork"
PASSWORD="YourStrongPassword"
STATIC_IP="192.168.4.1"
DHCP_RANGE_START="192.168.4.10"
DHCP_RANGE_END="192.168.4.100"

echo "📦 Updating and installing base packages..."
sudo apt update

echo "🧼 Ensuring Raspberry Pi–patched hostapd is installed..."
sudo apt purge -y hostapd
sudo apt install -y raspberrypi-kernel hostapd

echo "📦 Installing access point dependencies..."
sudo apt install -y dnsmasq netfilter-persistent iptables-persistent firmware-brcm80211

echo "🧹 Disabling wpa_supplicant on wlan0..."
sudo systemctl stop wpa_supplicant || true
sudo systemctl disable wpa_supplicant || true

echo "🧠 Configuring wlan0 static IP via systemd-networkd..."
sudo mkdir -p /etc/systemd/network
sudo tee /etc/systemd/network/10-wlan0.network > /dev/null <<EOF
[Match]
Name=wlan0

[Network]
Address=${STATIC_IP}/24
DHCPServer=yes

[DHCPServer]
PoolOffset=10
PoolSize=90
EOF

sudo systemctl enable systemd-networkd
sudo systemctl restart systemd-networkd

echo "🛠️ Configuring hostapd..."
sudo tee /etc/hostapd/hostapd.conf > /dev/null <<EOF
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
EOF

# Link hostapd config
sudo sed -i 's|#DAEMON_CONF=".*"|DAEMON_CONF="/etc/hostapd/hostapd.conf"|' /etc/default/hostapd

echo "🔧 Setting up dnsmasq..."
sudo mv /etc/dnsmasq.conf /etc/dnsmasq.conf.orig || true
sudo tee /etc/dnsmasq.conf > /dev/null <<EOF
interface=wlan0
dhcp-range=${DHCP_RANGE_START},${DHCP_RANGE_END},255.255.255.0,24h
address=/#/192.168.4.1
EOF

echo "📶 Unblocking Wi-Fi via rfkill..."
sudo rfkill unblock wifi
sudo rfkill unblock all

echo "🧩 Installing persistent RFKill unblock service..."
sudo tee /etc/systemd/system/unblock-wifi.service > /dev/null <<EOF
[Unit]
Description=Unblock Wi-Fi at boot
Before=network-pre.target
Wants=network-pre.target

[Service]
Type=oneshot
ExecStart=/usr/sbin/rfkill unblock wifi
ExecStart=/usr/sbin/rfkill unblock all

[Install]
WantedBy=multi-user.target
EOF

sudo systemctl daemon-reexec
sudo systemctl enable unblock-wifi.service

echo "🔐 Enabling and starting services..."
sudo systemctl unmask hostapd
sudo systemctl enable hostapd
sudo systemctl enable dnsmasq
sudo systemctl restart dnsmasq
sudo systemctl restart hostapd

echo "✅ Access point '${SSID}' is now live at ${STATIC_IP}"
