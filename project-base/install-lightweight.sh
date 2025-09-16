#!/bin/bash

echo "🚀 Instalando Shopsys Platform - Versión Optimizada para Desarrollo Local"
echo "=================================================================="

# Verificar que estamos en el directorio correcto
if [ ! -f "docker-compose.yml" ]; then
    echo "❌ Error: No se encontró docker-compose.yml. Ejecuta este script desde el directorio del proyecto."
    exit 1
fi

# Crear archivo de configuración local si no existe
if [ ! -f "build/build.local.properties" ]; then
    echo "📝 Creando configuración local..."
    mkdir -p build
    cat > build/build.local.properties << EOF
# Configuración optimizada para desarrollo local
elasticsearch.enabled=false
kibana.enabled=false
redis-commander.enabled=false
adminer.enabled=false
smtp-server.enabled=false
selenium.enabled=false
img-proxy.enabled=false
EOF
fi

# Iniciar solo los servicios esenciales
echo "🐳 Iniciando servicios esenciales..."
docker compose up -d postgres redis rabbitmq php-fpm webserver

# Esperar a que los servicios estén listos
echo "⏳ Esperando a que los servicios estén listos..."
sleep 10

# Verificar que los servicios estén funcionando
echo "🔍 Verificando servicios..."
if ! docker compose ps | grep -q "Up"; then
    echo "❌ Error: Los servicios no se iniciaron correctamente"
    exit 1
fi

echo "✅ Servicios iniciados correctamente"

# Instalar dependencias
echo "📦 Instalando dependencias..."
docker compose exec php-fpm composer install --no-dev --optimize-autoloader

# Crear directorios necesarios
echo "📁 Creando directorios..."
docker compose exec php-fpm php phing.phar dirs-create

# Compilar assets básicos
echo "🎨 Compilando assets..."
docker compose exec php-fpm php phing.phar npm-dev

# Configurar base de datos
echo "🗄️ Configurando base de datos..."
docker compose exec php-fpm php phing.phar db-import-basic-structure
docker compose exec php-fpm php phing.phar db-migrations
docker compose exec php-fpm php phing.phar domains-data-create

# Cargar datos básicos (sin demo data para ahorrar espacio)
echo "📊 Cargando datos básicos..."
docker compose exec php-fpm php phing.phar db-fixtures-demo

# Generar URLs amigables
echo "🔗 Generando URLs amigables..."
docker compose exec php-fpm php phing.phar friendly-urls-generate

echo ""
echo "🎉 ¡Instalación completada!"
echo "=================================================================="
echo "🌐 Frontend: http://localhost:8000"
echo "⚙️  Administración: http://localhost:8000/admin"
echo "📊 Base de datos: postgresql://root:root@localhost:5432/shopsys"
echo ""
echo "Para iniciar servicios adicionales cuando los necesites:"
echo "  docker compose --profile elasticsearch up -d"
echo "  docker compose --profile adminer up -d"
echo "  docker compose --profile redis-commander up -d"
echo ""
echo "Para detener todos los servicios:"
echo "  docker compose down"
echo "=================================================================="
