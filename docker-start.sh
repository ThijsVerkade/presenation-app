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

# Check if container is already running
if docker ps -q -f name=$CONTAINER_NAME | grep -q .; then
  echo "✅ Container '$CONTAINER_NAME' is already running."
else
  # Check if container exists but is stopped
  if docker ps -aq -f name=$CONTAINER_NAME | grep -q .; then
    echo "🔁 Starting existing stopped container '$CONTAINER_NAME'..."
    docker start $CONTAINER_NAME
  else
    # ▶️ Start with docker-compose
    echo "🚀 Starting Docker container with docker-compose..."
    echo "ℹ️  Using local images only (no internet connection required)..."

    # Disable BuildKit to prevent registry checks when offline
    sudo DOCKER_BUILDKIT=0 docker-compose up -d
  fi
fi

# Keep script running so systemd doesn't restart it unnecessarily
# Docker's restart policy will handle container restarts
echo "✅ Container started. Script will stay running to keep service active..."
exec sleep infinity
