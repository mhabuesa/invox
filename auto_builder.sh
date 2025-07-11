#!/bin/bash

echo "🚀 Starting Build Process..."

# Step 1: Install dependencies
echo "📦 Running composer install..."
composer install --no-dev --optimize-autoloader

# Step 2: Copy .env.example to .env
echo "⚙️ Creating .env file..."
cp .env.example .env

# Step 3: Generate Laravel app key
echo "🔐 Generating app key..."
php artisan key:generate

# Step 4: Make install.zip (excluding vendor, .git, etc.)
echo "📦 Creating install.zip..."
zip -r install.zip . -x  ".git/*" ".env" "node_modules/*" "storage/*" "install.zip" "build_install.sh"

echo "✅ Done! install.zip has been created."
