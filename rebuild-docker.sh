#!/bin/bash
set -e

echo "🔧 Rebuilding Docker container with increased upload limits..."

# Stop the current container
echo "🛑 Stopping current container..."
sudo docker-compose down

# Rebuild the container
echo "🔨 Rebuilding container..."
sudo DOCKER_BUILDKIT=1 COMPOSE_DOCKER_CLI_BUILD=1 docker-compose build --no-cache

# Start the container
echo "🚀 Starting container..."
sudo DOCKER_BUILDKIT=1 COMPOSE_DOCKER_CLI_BUILD=1 docker-compose up -d

# Fix storage permissions (run as root inside container)
echo "🔧 Fixing storage permissions..."
sudo docker-compose exec --user root app chown -R www-data:www-data storage bootstrap/cache
sudo docker-compose exec --user root app chmod -R ug+rw storage bootstrap/cache

echo "✅ Container rebuilt and started"
