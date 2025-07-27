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

# ▶️ Run Docker image loading and container start
echo "🚀 Starting Docker app..."
./docker-start.sh

# 🛠 Create environment file for systemd
echo "📄 Creating environment file for systemd..."
echo "APP_DIR=$APP_DIR" | sudo tee /etc/presentation.env > /dev/null

# 🛠 Create systemd service unit
echo "🛠 Creating systemd service unit..."
sudo tee /etc/systemd/system/presentation.service > /dev/null <<EOF
[Unit]
Description=Start Laravel Presentation App with Docker
After=network.target docker.service
Requires=docker.service

[Service]
Type=simple
ExecStart=${APP_DIR}/docker-start.sh
WorkingDirectory=${APP_DIR}
Restart=always

[Install]
WantedBy=multi-user.target
EOF

# Enable and start the systemd service
sudo systemctl daemon-reexec
sudo systemctl daemon-reload
sudo systemctl enable presentation.service
sudo systemctl start presentation.service

# ✅ Mark as installed
sudo touch /etc/presentation-installed.flag

echo "✅ Setup complete."
