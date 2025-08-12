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

# Ensure .env exists locally (create from example on first run)
if [ ! -f ".env" ]; then
  if [ -f ".env.example" ]; then
    echo "🧩 No .env found. Creating from .env.example..."
    cp .env.example .env
  else
    echo "❌ No .env or .env.example found. Create a .env file in the project root."
    exit 1
  fi
fi

# Optional: lock down permissions
chmod 600 .env || true

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

  export DOCKER_BUILDKIT=1
  export COMPOSE_DOCKER_CLI_BUILD=1

  sudo docker-compose up -d
fi

# (Optional) Generate APP_KEY on first boot if missing
if ! grep -q '^APP_KEY=' .env || grep -q '^APP_KEY=$' .env; then
  echo "🔐 Generating APP_KEY inside the container..."
  docker exec -u www-data "$CONTAINER_NAME" php artisan key:generate
fi
