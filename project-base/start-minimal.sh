#!/bin/bash

echo "🚀 Iniciando Shopsys Platform - Versión Mínima"
echo "=============================================="

# Iniciar solo servicios esenciales
echo "🐳 Iniciando servicios esenciales..."
docker compose up -d postgres redis rabbitmq php-fpm webserver

# Esperar a que estén listos
echo "⏳ Esperando a que los servicios estén listos..."
sleep 5

# Verificar estado
echo "🔍 Verificando servicios..."
docker compose ps

echo ""
echo "✅ Servicios iniciados:"
echo "🌐 Frontend: http://localhost:8000"
echo "⚙️  Administración: http://localhost:8000/admin"
echo ""
echo "Para iniciar servicios adicionales:"
echo "  docker compose --profile adminer up -d"
echo "  docker compose --profile redis-commander up -d"
echo "  docker compose --profile elasticsearch up -d"
echo ""
echo "Para detener: docker compose down"
