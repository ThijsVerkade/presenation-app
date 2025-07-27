#!/bin/bash
set -e

echo "🚀 Starting presentation system setup..."

# ✅ Skip install if already done
if [ -f /etc/presentation-installed.flag ]; then
  echo "🟡 System already installed. Skipping setup."
  exit 0
fi

# Set base directory where the repo was cloned
BASE_DIR="/home/thijs.verkade"
APP_DIR="$BASE_DIR/presentation-app"

# Ensure the app directory exists
if [ ! -d "$APP_DIR" ]; then
  echo "❌ App directory '$APP_DIR' not found. Make sure the repository is cloned correctly."
  exit 1
fi

# 🐳 Install Docker
echo "🐳 Installing Docker..."
curl -fsSL https://get.docker.com -o get-docker.sh
sh get-docker.sh
sudo usermod -aG docker thijs.verkade
sudo systemctl enable docker

# Make scripts executable
cd "$APP_DIR"
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
sudo bash -c "echo APP_DIR=$APP_DIR > /etc/presentation.env"

# 🛠 Create systemd service unit
echo "🛠 Creating systemd service unit..."
sudo bash -c "cat > /etc/systemd/system/presentation.service" <<EOF
[Unit]
Description=Start Laravel Presentation App with Docker
After=network.target docker.service
Requires=docker.service

[Service]
Type=simple
EnvironmentFile=/etc/presentation.env
ExecStart=\${APP_DIR}/docker-start.sh
WorkingDirectory=\${APP_DIR}
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
