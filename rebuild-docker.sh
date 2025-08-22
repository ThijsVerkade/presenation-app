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

echo "✅ Container rebuilt and started"
