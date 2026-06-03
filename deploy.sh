#!/bin/bash

set -e

echo "Pulling latest code..."
git pull origin main

echo "Restarting Apache..."
sudo systemctl restart apache2

echo "Deployment completed successfully."
