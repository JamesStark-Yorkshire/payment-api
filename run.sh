copy .env.example .env
docker-compose up -d
chmod -R 775 storage
docker exec -it paymentapi_app_1 php artisan migrate:fresh --seed