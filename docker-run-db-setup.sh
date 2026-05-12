#!/bin/bash
set -euo pipefail

# Run the database setup from the app container.
# This is intended for a docker-compose deployment.

sudo docker compose exec app bash -lc "cd /var/www/html && ./database/setup.sh"
