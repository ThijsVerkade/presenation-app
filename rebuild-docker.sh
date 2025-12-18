#!/bin/bash
set -e

echo "🔧 Rebuilding Docker container with increased upload limits..."

# Stop the current container
echo "🛑 Stopping current container..."
sudo docker-compose down

# Rebuild the container (using local images only, no internet pull)
# Disable BuildKit to prevent registry checks when offline
# Note: Without --no-cache, Docker uses cached layers (apt-get layer won't re-download packages)
echo "🔨 Rebuilding container..."
echo "ℹ️  Using local images only (no internet connection required)..."
echo "ℹ️  Using cached layers to avoid downloading packages..."
sudo DOCKER_BUILDKIT=0 docker-compose build

# Start the container
echo "🚀 Starting container..."
sudo DOCKER_BUILDKIT=0 docker-compose up -d

# Fix storage permissions (run as root inside container)
echo "🔧 Fixing storage permissions..."
sudo docker-compose exec --user root app chown -R www-data:www-data storage bootstrap/cache
sudo docker-compose exec --user root app chmod -R ug+rw storage bootstrap/cache

echo "✅ Container rebuilt and started"
