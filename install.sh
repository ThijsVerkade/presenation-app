#!/bin/bash
set -e

echo "🚀 Starting presentation system setup..."

# ✅ Skip install if already done
if [ -f /etc/presentation-installed.flag ]; then
  echo "🟡 System already installed. Skipping setup."
  exit 0
fi

# Mount point of the USB
BASE_DIR="/media/pi/Lexar/presentation-usb"

# 📁 Copy project to home directory
echo "📁 Copying project to /home/pi/presentation-app..."
cp -r "$BASE_DIR/presentation-app" /home/pi/presentation-app

# 🐳 Install Docker
echo "🐳 Installing Docker..."
curl -fsSL https://get.docker.com -o get-docker.sh
sh get-docker.sh
sudo usermod -aG docker pi
sudo systemctl enable docker

# Make scripts executable
cd /home/pi/presentation-app
chmod +x setup-access-point.sh enable-external-access.sh docker-start.sh docker-install.sh

# 📡 Setup WiFi access point
echo "📡 Setting up access point..."
./setup-access-point.sh

# 🌐 Enable optional internet sharing
echo "🌐 Configuring optional external access..."
./enable-external-access.sh

# ▶️ Run Docker image loading and container start
echo "🚀 Starting Docker app..."
./docker-install.sh

# 🛠 Copy and enable Laravel auto-start service
echo "🛠 Installing Laravel auto-start service..."
cp presentation.service /etc/systemd/system/
systemctl daemon-reexec
systemctl enable presentation.service
systemctl start presentation.service

# 🔄 Enable USB auto-reinstall for future
echo "🔧 Enabling USB auto-install for future devices..."
cp "$BASE_DIR/usb-autoinstall.service" /etc/systemd/system/
systemctl daemon-reexec
systemctl enable usb-autoinstall.service

# ✅ Mark as installed
touch /etc/presentation-installed.flag

echo "✅ Setup complete! Rebooting..."
reboot
