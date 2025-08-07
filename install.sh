#!/bin/bash
set -e

echo "🚀 Starting presentation system setup..."

# ✅ Skip install if already done
if [ -f /etc/presentation-installed.flag ]; then
  echo "🟡 System already installed. Skipping setup."
  exit 0
fi

# Detect APP_DIR as current directory
APP_DIR="$(pwd)"
APP_USER="$(whoami)"

# 🐳 Install Docker
echo "🐳 Installing Docker..."
curl -fsSL https://get.docker.com -o get-docker.sh
sh get-docker.sh
sudo usermod -aG "$APP_USER" "$APP_USER"
sudo systemctl enable docker

# Make scripts executable
chmod +x setup-access-point.sh enable-external-access.sh docker-start.sh

# 📡 Setup WiFi access point
echo "📡 Setting up access point..."
./setup-access-point.sh

# 🌐 Enable optional internet sharing
echo "🌐 Configuring optional external access..."
./enable-external-access.sh

# 🛠 Ensuring database.sqlite is ready...
mkdir -p database
if [ -d database/database.sqlite ]; then
  echo "❌ Found a directory instead of a database file. Fixing..."
  sudo rm -rf database/database.sqlite
fi
if [ ! -f database/database.sqlite ]; then
  echo "📄 Creating database file..."
  sudo touch database/database.sqlite
fi
sudo chmod 777 database
sudo chmod 666 database/database.sqlite

# ▶️ Run Docker image loading and container start
echo "🚀 Starting Docker app..."
./docker-start.sh

# 🛠 Create environment file for systemd
echo "📄 Creating environment file for systemd..."
echo "APP_DIR=$APP_DIR" | sudo tee /etc/presentation.env > /dev/null

# 🛠 Copy and enable Laravel auto-start service
echo "🛠 Installing Laravel auto-start service..."
sudo cp presentation.service /etc/systemd/system/

# 🛠 Installing WiFi setup auto-start service
echo "🛠 Installing WiFi setup service..."
sudo cp setup-wifi.service /etc/systemd/system/

# Blacklist acer-wmi just in case
echo "blacklist acer-wmi" | sudo tee -a /etc/modprobe.d/blacklist.conf

sudo bash -c 'cat > /etc/systemd/system/unblock-wifi.service <<EOF
[Unit]
Description=Unblock WiFi on boot
After=network.target

[Service]
Type=oneshot
ExecStart=/usr/sbin/rfkill unblock wifi

[Install]
WantedBy=multi-user.target
EOF'

# Enable and start the systemd service
sudo systemctl daemon-reexec
sudo systemctl daemon-reload
sudo systemctl enable presentation.service
sudo systemctl enable setup-wifi.service
sudo systemctl enable unblock-wifi.service
sudo systemctl start presentation.service

# ✅ Mark as installed
sudo touch /etc/presentation-installed.flag

echo "✅ Setup complete."
