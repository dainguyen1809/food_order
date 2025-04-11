FROM php:8.2-alpine

WORKDIR /app

COPY ../composer.json ../composer.lock* ./

COPY . .

EXPOSE 8001

CMD ["php", "artisan", "serve"]
