## Requirements

- PHP 8.1
- MySQL 8.0
- node 18.16.0
- npm 9.5.1

## Deploy instructions

- run `git clone git@github.com:protectedfor/postavleno.git`
- cd postavleno
- cp `.env.example .env`
- create database
- setup `.env` with database credentials
- run `composer install`
- run `php artisan migrate`
- run `npm install`
- run `npm run dev`
- register on site
- Wuolya!
