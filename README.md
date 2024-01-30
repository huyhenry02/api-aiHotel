### Work with docker
- To start working environment, please run `docker-compose up --build -d`
- To access laravel app, access address `http://localhost:8122`
>docker exec -it ai_hotel-api chmod -R 777 storage/logs/laravel.log

>docker exec -it ai_hotel-api chmod -R 777 storage/framework

>docker exec -it ai_hotel-api chmod -R 777 storage/app/public
- To access database, please use credentials following:
> Hostname: localhost
>
> Port: 8431
>
> Username: ai_hotel
>
> Password: ai_hotel

### API documentation
- To access swagger, access address `http://localhost:8122/api/documentation`
