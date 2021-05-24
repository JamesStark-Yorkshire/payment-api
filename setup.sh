cp .env.example .env
docker-compose up -d
docker exec -it payment-api_app php artisan migrate:fresh --seed