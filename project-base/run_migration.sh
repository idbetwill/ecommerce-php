#!/bin/bash
echo "Esperando que Docker esté listo..."
sleep 10

echo "Ejecutando migración..."
docker compose exec php-fpm php phing db-migrations

echo "Limpiando cache..."
docker compose exec php-fpm php bin/console cache:clear

echo "¡Migración completada!"
echo "Puedes acceder a la página de Import Logs en: http://127.0.0.1:8000/admin/importlog/list/"
