#!/usr/bin/env bash

echo "Working directory: ~/cocoabay"
cd ~/

if [ ! -d "cocoabay" ]; then
    git clone git@github.com:rothso/cocoabay.git cocoabay

    # Symlink the public directory to public_html ("portal" is our subdomain folder)
    cd ~/www/cocoabay.net
    ln -s ~/cocoabay/public portal
else
    cd ~/cocoabay
    git pull
fi

cd ~/cocoabay

# Sync the .env file generated during the CircleCI build
echo "Syncing .env file"
cp /tmp/.env .env

# Laravel stuff
composer.phar install --optimize-autoloader
composer.phar dump-autoload
npm install
npm run production
php artisan config:cache
php artisan route:cache
php artisan migrate --force