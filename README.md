### Work with docker
- To start working environment, please run `docker compose up -d`
- To access laravel app, access address `http://localhost:8122`
>docker exec -it ai_hotel-api chmod -R 777 storage/logs/laravel.log

>docker exec -it ai_hotel-api chmod -R 777 storage/framework

>docker exec -it ai_hotel-api chmod -R 777 storage/app/public
- To run project, please run 
>docker exec -it ai_hotel-api composer install
- To access database, please use credentials following:
>docker exec -it ai_hotel-api php artisan migrate

>docker exec -it ai_hotel-api php artisan passport:install --force

> Hostname: localhost
>
> Port: 8431
>
> Username: ai_hotel
>
> Password: ai_hotel
- To seed user admin, please run
>docker exec -it ai_hotel-api php artisan db:seed
### API documentation
- To access swagger, access address `http://localhost:8122/api/documentation`
