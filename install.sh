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

# 📁 Copy Laravel app to /var/www/html
echo "📁 Copying project to /var/www/html..."
sudo rm -rf /var/www/html
sudo mkdir -p /var/www/html
sudo cp -r "$BASE_DIR/presentation-app/"* /var/www/html
sudo chown -R www-data:www-data /var/www/html

# 🌐 Install PHP, Nginx, and Laravel dependencies
sudo apt update
sudo apt install -y php php-fpm php-mbstring php-xml php-bcmath php-curl php-zip php-sqlite3 unzip nginx

# 🛠 Configure permissions for Laravel
cd /var/www/html
mkdir -p storage/logs
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# 🧠 Set up SQLite database (optional, if used)
touch database/database.sqlite
chown www-data:www-data database/database.sqlite

# 🌐 Configure Nginx for Laravel
echo "🛠 Setting up Nginx config..."
cat <<EOF | sudo tee /etc/nginx/sites-available/laravel
server {
    listen 80;
    server_name localhost;
    root /var/www/html/public;

    index index.php index.html;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php7.4-fpm.sock;
    }

    location ~ /\.ht {
        deny all;
    }
}
EOF

# Enable Nginx site and restart services
sudo ln -sf /etc/nginx/sites-available/laravel /etc/nginx/sites-enabled/default
sudo systemctl restart nginx
sudo systemctl restart php7.4-fpm

# 📡 Setup WiFi access point
echo "📡 Setting up access point..."
cd "$BASE_DIR"
chmod +x setup-access-point.sh enable-external-access.sh
./setup-access-point.sh

# 🌍 Enable optional external access
echo "🌐 Configuring optional external access..."
./enable-external-access.sh

# 🔄 Enable USB auto-reinstall service
echo "🔧 Enabling USB auto-install for future devices..."
cp "$BASE_DIR/usb-autoinstall.service" /etc/systemd/system/
sudo systemctl daemon-reexec
sudo systemctl enable usb-autoinstall.service

# ✅ Mark as installed
touch /etc/presentation-installed.flag

echo "✅ Setup complete! Rebooting..."
reboot
