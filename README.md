# social-laravel

## tecnologies used since last update

Backend, an API with:<br>
PHP 8.2<br>
Laravel 10<br>
PostgreSQL<br>
PestPHP<br>
docker<br>
docker-compose<br>
TODO: Swagger/Open API Documentation<br>

## features added since last update

Social Login with socialite, using Twitter as base. <br>
To use this feature you will need to provide your secrets and tokens generated from twitter developers pages at the env of this project.<br>

To run this project just open your terminal on this root folder and: <br>
1 - docker-compose build <br>
2 - docker-compose up -d  <br>
3 - docker-compose exec app_curotec composer install <br>
4 - docker-compose exec app_curotec php artisan migrate <br>
5 - docker-compose exec app_curotec php artisan db:seed <br>

if you want to run the backend tests you can do: <br>
docker-compose exec app_curotec php artisan test <br>

# frontend 
WORK IN PROGRESS, you can see on the DRAFT PULL REQUEST