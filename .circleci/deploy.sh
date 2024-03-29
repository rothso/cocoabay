#!/usr/bin/env bash

echo "Working directory: ~/cocoabay"
cd ~/

if [ ! -d "cocoabay" ]; then
    git clone git@github.com:rothso/cocoabay.git cocoabay

    # Symlink the public directory to public_html ("portal" is our subdomain folder)
    echo "Creating symbolic link from public to www/cocoabay.net/portal"
    cd ~/www/cocoabay.net
    ln -s ~/cocoabay/public portal

    # Symlink the public storage to the public directory
    echo "Creating symbolic link from public/storage to storage/app/public"
    cd ~/cocoabay/public
    ln -s ~/cocoabay/storage/app/public storage
else
    cd ~/cocoabay

    # Get rid of any local changes, as they cause pulls to abort.
    git stash

    git pull
fi

cd ~/cocoabay

# Sync the .env file generated during the CircleCI build
echo "Syncing .env file"
cp /tmp/.env .env

# Laravel stuff
composer.phar install --no-dev
npm install
npm run production
php artisan config:cache
php artisan route:cache
php artisan migrate --force