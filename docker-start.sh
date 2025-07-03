#!/bin/bash

if [ -f /home/pi/presentation-app/presentation-app.tar ]; then
  echo "🔄 Loading Docker image from presentation-app.tar..."
  docker load < /home/pi/presentation-app/presentation-app.tar
fi

# Navigate to project directory
cd /home/pi/presentation-app || exit 1

# Start Docker containers
/usr/bin/docker-compose up -d
