#!/bin/bash
set -e

sudo rfkill unblock all
sudo ip link set wlan0 up
