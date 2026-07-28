#!/usr/bin/env bash
set -e

echo "[1/9] Installing PHP dependencies..."
composer install --no-interaction --prefer-dist

echo "[2/9] Creating .env file if it doesn't exist..."
if [ ! -f .env ]; then
    cp .env.example .env
    echo ".env created. Please edit it with your database credentials."
else
    echo ".env already exists."
fi

echo "[3/9] Generating application key..."
php artisan key:generate

echo "[4/9] Please make sure your MySQL database 'medcenter' is created."
echo "  Run this in MySQL: CREATE DATABASE medcenter CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
echo "  Then update DB_USERNAME and DB_PASSWORD in the .env file."
echo ""
read -p "Press Enter after you've configured the database..."

echo "[5/9] Running database migrations and seeders..."
php artisan migrate --seed

echo "[6/9] Installing Node.js dependencies..."
npm install

echo "[7/9] Building frontend assets..."
npm run build

echo "[8/9] Creating storage symlink..."
php artisan storage:link

echo "[9/9] Clearing cache..."
php artisan optimize:clear

echo ""
echo "========================================"
echo "Setup complete!"
echo "Run: php artisan serve"
echo "Then visit: http://localhost:8000"
echo "========================================"