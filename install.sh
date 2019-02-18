#!/bin/bash
composer install
npm install
cd semantic/
gulp build
cd ..
mkdir ./public/images/profile_pictures
mkdir ./public/papers_files
php artisan key:generate
php artisan migrate:refresh --seed
npm run prod
