# Shopsys Platform - Instalación Optimizada para Desarrollo Local

Esta configuración está optimizada para usar menos recursos y ser más rápida en desarrollo local.

## 🚀 Instalación Rápida

### Opción 1: Instalación Automática (Recomendada)
```bash
./install-lightweight.sh
```

### Opción 2: Instalación Manual
```bash
# 1. Iniciar solo servicios esenciales
docker compose up -d postgres redis rabbitmq php-fpm webserver

# 2. Instalar dependencias
docker compose exec php-fpm composer install --no-dev --optimize-autoloader

# 3. Crear directorios
docker compose exec php-fpm php phing.phar dirs-create

# 4. Compilar assets
docker compose exec php-fpm php phing.phar npm-dev

# 5. Configurar base de datos
docker compose exec php-fpm php phing.phar db-import-basic-structure
docker compose exec php-fpm php phing.phar db-migrations
docker compose exec php-fpm php phing.phar domains-data-create

# 6. Cargar datos de demostración
docker compose exec php-fpm php phing.phar db-fixtures-demo

# 7. Generar URLs amigables
docker compose exec php-fpm php phing.phar friendly-urls-generate
```

## 🎯 Servicios Incluidos (Mínimos)

- **PostgreSQL** - Base de datos principal
- **Redis** - Cache y sesiones
- **RabbitMQ** - Colas de mensajes
- **PHP-FPM** - Aplicación PHP
- **Nginx** - Servidor web

## 🔧 Servicios Opcionales (Bajo Demanda)

Para activar servicios adicionales cuando los necesites:

```bash
# Base de datos web (Adminer)
docker compose --profile adminer up -d

# Redis web interface
docker compose --profile redis-commander up -d

# Elasticsearch (para búsquedas avanzadas)
docker compose --profile elasticsearch up -d

# Servidor de correo
docker compose --profile smtp up -d

# Selenium (para pruebas)
docker compose --profile selenium up -d
```

## 📊 Uso de Recursos

### Configuración Mínima
- **RAM**: ~1.5GB
- **CPU**: 2 cores
- **Disco**: ~2GB

### Con Servicios Adicionales
- **RAM**: ~3GB
- **CPU**: 4 cores
- **Disco**: ~4GB

## 🌐 URLs de Acceso

- **Frontend**: http://localhost:8000
- **Administración**: http://localhost:8000/admin
- **Adminer** (si está activo): http://localhost:1100
- **Redis Commander** (si está activo): http://localhost:1600

## 🛠️ Comandos Útiles

```bash
# Iniciar servicios mínimos
./start-minimal.sh

# Ver logs
docker compose logs -f

# Acceder al contenedor PHP
docker compose exec php-fpm bash

# Limpiar cache
docker compose exec php-fpm php phing.phar clean-cache

# Recompilar assets
docker compose exec php-fpm php phing.phar npm-dev

# Detener todos los servicios
docker compose down

# Detener y eliminar volúmenes (CUIDADO: elimina datos)
docker compose down -v
```

## ⚡ Optimizaciones Aplicadas

1. **Servicios deshabilitados por defecto**: Elasticsearch, Kibana, Adminer, etc.
2. **Límites de memoria**: Configurados para usar menos RAM
3. **Assets optimizados**: Compilación en modo desarrollo
4. **Cache optimizado**: Configuración de Redis para desarrollo
5. **Base de datos ligera**: Solo datos esenciales cargados

## 🔍 Solución de Problemas

### Si la aplicación no carga:
```bash
# Verificar logs
docker compose logs webserver
docker compose logs php-fpm

# Reiniciar servicios
docker compose restart
```

### Si hay problemas de memoria:
```bash
# Verificar uso de recursos
docker stats

# Ajustar límites en docker-compose.override.yml
```

### Si la base de datos no funciona:
```bash
# Verificar conexión
docker compose exec postgres psql -U root -d shopsys -c "SELECT 1;"
```

## 📝 Notas Importantes

- Esta configuración es para **desarrollo local** únicamente
- Para producción, usa la configuración completa
- Los datos se mantienen en volúmenes Docker
- Para resetear completamente: `docker compose down -v && ./install-lightweight.sh`
