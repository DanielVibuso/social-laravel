# social-laravel

To run this project just open your terminal on this root folder and:
1 - docker-compose build
2 - docker-compose up -d
3 - docker-compose exec app_curotec composer install
4 - docker-compose exec app_curotec php artisan migrate
5 - docker-compose exec app_curotec php artisan db:seed

if you want to run the backend tests you can do:

docker-compose exec app_curotec php artisan test