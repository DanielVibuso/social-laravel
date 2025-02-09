# social-laravel

## tecnologies used since last update

Backend, an API with:<br>
PHP 8.2<br>
Laravel 10<br>
PostgreSQL<br>
PestPHP<br>
docker<br>
docker-compose<br>
Scramble: Swagger/Open API Documentation<br>

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

You can access the documentation of the endpoints in the url: http://localhost/docs/api#/  <br>

I moved the Socialite login routes in my project to web.php because Twitter <br> authentication requires session support, which is not available in api.php since <br> API routes are stateless by default. This was one of the blockers for a <br>smooth flow during the assessment, as social networks have very particular<br> behaviors. For scalability, a possible approach could be using the <br>Strategy pattern with a separate service for each login. However, due to the <br>previously mentioned difficulties in obtaining good documentation and free <br>access to these APIs, I focused solely on implementing the basic functionality.

<br>
I decide use Scramble to create de routes documentation, and it does not got web routes for some reason, then to make the social login manually you should put at your browser : <br>
localhost/auth/twitter/redirect <br>

after that, if you put your secrets and tokens at .env, the callback endpoint will output some json data, including the bearer token.

# frontend 
WORK IN PROGRESS, you can see on the DRAFT PULL REQUEST