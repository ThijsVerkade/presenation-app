#!/bin/bash
set -e

IMAGE_NAME="presentation-app"
CONTAINER_NAME="presentation-app"

# Ensure docker-compose is installed
if ! command -v docker-compose &> /dev/null; then
  echo "🚧 docker-compose not found, installing..."
  sudo apt update
  sudo apt install -y docker-compose
fi

# 🛑 Stop and remove any existing container
if [ "$(docker ps -aq -f name=$CONTAINER_NAME)" ]; then
  echo "♻️ Stopping and removing existing container..."
  docker stop $CONTAINER_NAME || true
  docker rm $CONTAINER_NAME || true
fi

# ▶️ Start with docker-compose
echo "🚀 Starting Docker container with docker-compose..."
cd /home/thijs.verkade/presentation-app
sudo docker-compose up -d
