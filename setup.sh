#!/bin/bash
sudo rfkill unblock all
sudo ip link set wlan0 up
sudo systemctl restart hostapd
