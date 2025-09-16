#!/bin/bash

echo "🚀 Script de Despliegue - Shopsys Platform CRUD"
echo "================================================"

# Colores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Función para mostrar mensajes
show_message() {
    echo -e "${GREEN}✅ $1${NC}"
}

show_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

show_error() {
    echo -e "${RED}❌ $1${NC}"
}

# Verificar que estamos en el directorio correcto
if [ ! -f "composer.json" ]; then
    show_error "No se encontró composer.json. Ejecuta este script desde el directorio del proyecto."
    exit 1
fi

echo ""
echo "📋 OPCIONES DE DESPLIEGUE GRATUITO:"
echo "=================================="
echo ""
echo "1. 🚂 Railway (Recomendado)"
echo "   • $5 crédito mensual gratis"
echo "   • PHP + MySQL/PostgreSQL"
echo "   • Deploy automático desde GitHub"
echo "   • SSL incluido"
echo ""
echo "2. 🎨 Render"
echo "   • 750 horas/mes gratis"
echo "   • PHP + PostgreSQL"
echo "   • Auto-deploy desde GitHub"
echo ""
echo "3. 🟣 Heroku"
echo "   • 550-1000 horas/mes gratis"
echo "   • PHP + PostgreSQL"
echo "   • Add-ons gratuitos"
echo ""
echo "4. ♾️  InfinityFree"
echo "   • Completamente gratis"
echo "   • PHP + MySQL"
echo "   • Sin límites de tiempo"
echo ""

# Preparar archivos para despliegue
show_message "Preparando archivos para despliegue..."

# Crear archivo .gitignore si no existe
if [ ! -f ".gitignore" ]; then
    cat > .gitignore << EOF
# Archivos de entorno
.env
.env.local
.env.prod

# Cache y logs
var/cache/*
var/log/*
var/sessions/*

# Uploads
web/uploads/*

# Node modules
node_modules/

# Vendor (se instala en el servidor)
vendor/

# Archivos temporales
*.tmp
*.log
EOF
    show_message "Archivo .gitignore creado"
fi

# Crear README de despliegue
cat > DEPLOY.md << 'EOF'
# 🚀 Guía de Despliegue - Shopsys Platform CRUD

## Opciones de Hosting Gratuito

### 1. Railway (Recomendado)

1. **Crear cuenta en Railway:**
   - Ve a [railway.app](https://railway.app)
   - Conecta tu cuenta de GitHub

2. **Crear nuevo proyecto:**
   - Click en "New Project"
   - Selecciona "Deploy from GitHub repo"
   - Elige este repositorio

3. **Configurar base de datos:**
   - Click en "New" → "Database" → "PostgreSQL"
   - Railway creará automáticamente las variables de entorno

4. **Configurar variables de entorno:**
   ```
   APP_ENV=prod
   APP_DEBUG=false
   APP_SECRET=tu-clave-secreta-aqui
   DATABASE_URL=postgresql://usuario:password@host:puerto/database
   ```

5. **Deploy automático:**
   - Railway detectará el Dockerfile.railway
   - El deploy comenzará automáticamente

### 2. Render

1. **Crear cuenta en Render:**
   - Ve a [render.com](https://render.com)
   - Conecta tu cuenta de GitHub

2. **Crear Web Service:**
   - Click en "New" → "Web Service"
   - Conecta tu repositorio
   - Usa el Dockerfile.railway

3. **Configurar base de datos:**
   - Click en "New" → "PostgreSQL"
   - Copia la DATABASE_URL

4. **Variables de entorno:**
   ```
   APP_ENV=prod
   APP_DEBUG=false
   APP_SECRET=tu-clave-secreta
   DATABASE_URL=postgresql://usuario:password@host:puerto/database
   ```

### 3. Heroku

1. **Instalar Heroku CLI:**
   ```bash
   # Ubuntu/Debian
   curl https://cli-assets.heroku.com/install.sh | sh
   
   # macOS
   brew tap heroku/brew && brew install heroku
   ```

2. **Login y crear app:**
   ```bash
   heroku login
   heroku create tu-app-name
   ```

3. **Agregar base de datos:**
   ```bash
   heroku addons:create heroku-postgresql:hobby-dev
   ```

4. **Configurar variables:**
   ```bash
   heroku config:set APP_ENV=prod
   heroku config:set APP_DEBUG=false
   heroku config:set APP_SECRET=tu-clave-secreta
   ```

5. **Deploy:**
   ```bash
   git push heroku main
   ```

### 4. InfinityFree

1. **Crear cuenta:**
   - Ve a [infinityfree.net](https://infinityfree.net)
   - Crea una cuenta gratuita

2. **Crear hosting:**
   - Click en "Create Account"
   - Elige un subdominio

3. **Subir archivos:**
   - Usa File Manager o FTP
   - Sube todos los archivos del proyecto

4. **Configurar base de datos:**
   - Ve a "MySQL Databases"
   - Crea una nueva base de datos
   - Crea un usuario y asigna permisos

5. **Configurar .env:**
   - Edita el archivo .env con los datos de la base de datos

## Comandos Post-Deploy

Una vez desplegado, ejecuta estos comandos:

```bash
# Instalar dependencias
composer install --no-dev --optimize-autoloader

# Ejecutar migraciones
php bin/console doctrine:migrations:migrate --no-interaction

# Limpiar cache
php bin/console cache:clear --env=prod

# Crear directorios necesarios
mkdir -p var/cache var/log var/sessions web/uploads
chmod -R 755 var web/uploads
```

## URLs de Acceso

- **Frontend:** https://tu-dominio.com
- **Admin Productos:** https://tu-dominio.com/admin/products
- **Admin Categorías:** https://tu-dominio.com/admin/categories

## Solución de Problemas

### Error de permisos:
```bash
chmod -R 755 var web/uploads
```

### Error de base de datos:
- Verifica que DATABASE_URL esté correctamente configurada
- Asegúrate de que la base de datos esté creada

### Error de cache:
```bash
php bin/console cache:clear --env=prod
```

## Soporte

Si tienes problemas con el despliegue, revisa:
1. Los logs del hosting
2. Las variables de entorno
3. La configuración de la base de datos
4. Los permisos de archivos
EOF

show_message "Guía de despliegue creada (DEPLOY.md)"

# Crear archivo de configuración para diferentes hostings
cat > hosting-configs.md << 'EOF'
# Configuraciones por Hosting

## Railway
- **Dockerfile:** Dockerfile.railway
- **Puerto:** 8000
- **Base de datos:** PostgreSQL (automática)
- **Variables:** APP_ENV=prod, APP_DEBUG=false

## Render
- **Dockerfile:** Dockerfile.railway
- **Puerto:** 8000
- **Base de datos:** PostgreSQL
- **Variables:** APP_ENV=prod, APP_DEBUG=false

## Heroku
- **Buildpack:** heroku/php
- **Puerto:** $PORT (automático)
- **Base de datos:** PostgreSQL (addon)
- **Variables:** APP_ENV=prod, APP_DEBUG=false

## InfinityFree
- **PHP:** 8.3
- **Base de datos:** MySQL
- **Upload:** File Manager
- **Variables:** Editar .env manualmente
EOF

show_message "Configuraciones de hosting creadas"

echo ""
show_message "¡Archivos preparados para despliegue!"
echo ""
echo "📁 Archivos creados:"
echo "• deploy-config.env - Configuración de producción"
echo "• railway.json - Configuración para Railway"
echo "• Dockerfile.railway - Dockerfile optimizado"
echo "• DEPLOY.md - Guía completa de despliegue"
echo "• hosting-configs.md - Configuraciones por hosting"
echo ""
echo "🚀 PRÓXIMOS PASOS:"
echo "=================="
echo ""
echo "1. 📤 Subir a GitHub:"
echo "   git add ."
echo "   git commit -m 'Preparado para despliegue'"
echo "   git push origin main"
echo ""
echo "2. 🚂 Desplegar en Railway (Recomendado):"
echo "   • Ve a railway.app"
echo "   • Conecta tu GitHub"
echo "   • Deploy automático"
echo ""
echo "3. 🎨 Desplegar en Render:"
echo "   • Ve a render.com"
echo "   • Conecta tu GitHub"
echo "   • Usa Dockerfile.railway"
echo ""
echo "4. 📖 Leer DEPLOY.md para instrucciones detalladas"
echo ""
show_message "¡Listo para desplegar! 🎉"
