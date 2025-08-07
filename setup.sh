#!/bin/bash
set -e

echo "🕒 Checking timezone and system clock..."

# Desired timezone (change if needed)
DESIRED_TZ="Europe/London"

# Check and set timezone
CURRENT_TZ=$(timedatectl show --value --property=Timezone)
if [ "$CURRENT_TZ" != "$DESIRED_TZ" ]; then
  echo "🔄 Setting timezone to $DESIRED_TZ"
  timedatectl set-timezone "$DESIRED_TZ"
else
  echo "✅ Timezone already set to $CURRENT_TZ"
fi

# Disable NTP (since we're offline)
echo "❌ Disabling NTP (offline mode)"
timedatectl set-ntp false

# Optional: Warn if system clock is obviously wrong
YEAR=$(date +%Y)
if [ "$YEAR" -lt 2024 ]; then
  echo "⚠️ WARNING: System clock may be invalid ($YEAR)"
  echo "🔧 Consider setting the date manually with: sudo date -s 'YYYY-MM-DD HH:MM:SS'"
fi

# Bring up Wi-Fi interface
echo "📶 Bringing up wlan0..."
rfkill unblock wifi
rfkill unblock all
ip link set wlan0 up
