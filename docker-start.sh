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
  echo "✅ Container '$CONTAINER_NAME' is already running. Skipping start."
  exit 0
fi

# Check if container exists but is stopped
if docker ps -aq -f name=$CONTAINER_NAME | grep -q .; then
  echo "🔁 Starting existing stopped container '$CONTAINER_NAME'..."
  docker start $CONTAINER_NAME
else
  # ▶️ Start with docker-compose
  echo "🚀 Starting Docker container with docker-compose..."

  # Enable BuildKit universally
  export DOCKER_BUILDKIT=1
  export COMPOSE_DOCKER_CLI_BUILD=1

  sudo docker-compose up -d
  sudo docker-compose exec $CONTAINER_NAME composer install
  sudo docker-compose exec $CONTAINER_NAME php artisan migrate --force
fi
