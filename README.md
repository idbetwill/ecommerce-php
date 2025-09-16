# 🛒 Ecommerce PHP - Shopsys Platform con CRUD de Productos

Un sistema de ecommerce completo basado en Shopsys Platform con un sistema CRUD personalizado para gestión de productos y categorías.

## 📋 Tabla de Contenidos

- [Características](#-características)
- [Requisitos del Sistema](#-requisitos-del-sistema)
- [Instalación](#-instalación)
- [Configuración](#-configuración)
- [Migraciones de Base de Datos](#-migraciones-de-base-de-datos)
- [Sistema CRUD](#-sistema-crud)
- [Uso del Sistema](#-uso-del-sistema)
- [Estructura del Proyecto](#-estructura-del-proyecto)
- [Desarrollo](#-desarrollo)
- [Solución de Problemas](#-solución-de-problemas)

## ✨ Características

- **🛍️ Sistema Ecommerce Completo** - Basado en Shopsys Platform
- **📦 CRUD de Productos** - Gestión completa de productos con campos personalizados
- **🏷️ Categorías Personalizadas** - Sistema de categorías con jerarquía
- **🔧 Campos Personalizados** - SKU, peso, dimensiones, fabricante, garantía, etc.
- **🎨 Interfaz Moderna** - Admin panel responsive y fácil de usar
- **🔒 Seguridad** - Validaciones y protección CSRF
- **📱 Responsive** - Compatible con dispositivos móviles

## 🖥️ Requisitos del Sistema

- **PHP** 8.3 o superior
- **Composer** 2.0 o superior
- **Docker** y **Docker Compose**
- **Node.js** 18+ y **npm**
- **Git**

## 🚀 Instalación

### 1. Clonar el Repositorio

```bash
git clone https://github.com/idbetwill/ecommerce-php.git
cd ecommerce-php
```

### 2. Inicializar Submodule

```bash
git submodule update --init --recursive
```

### 3. Instalar Dependencias

```bash
cd project-base
composer install
npm install
```

### 4. Configurar Docker

```bash
# Copiar configuración de Docker
cp docker/conf/docker-compose.yml.dist docker-compose.yml

# Configurar UID y GID para permisos correctos
echo "UID=$(id -u)" >> .env
echo "GID=$(id -g)" >> .env
```

### 5. Iniciar Servicios

```bash
# Iniciar servicios esenciales
docker compose up -d postgres redis rabbitmq php-fpm webserver

# Esperar a que los servicios estén listos
sleep 10
```

### 6. Configurar Base de Datos

```bash
# Instalar dependencias
docker compose exec php-fpm composer install

# Descargar Phing
docker compose exec php-fpm php app/downloadPhing.php --phing-version=3.0.0

# Crear directorios
docker compose exec php-fpm php phing.phar dirs-create

# Instalar assets
docker compose exec php-fpm php phing.phar assets

# Generar traducciones
docker compose exec php-fpm php bin/console shopsys:translations:frontend:dump

# Compilar assets de frontend
docker compose exec php-fpm npm install
docker compose exec php-fpm php phing.phar npm-dev

# Configurar base de datos
docker compose exec php-fpm php phing.phar db-import-basic-structure
docker compose exec php-fpm php phing.phar db-migrations
docker compose exec php-fpm php phing.phar domains-db-functions-create
docker compose exec php-fpm php phing.phar domains-data-create

# Cargar datos de demostración
docker compose exec php-fpm php phing.phar db-fixtures-demo

# Generar URLs amigables
docker compose exec php-fpm php phing.phar friendly-urls-generate
```

## ⚙️ Configuración

### Variables de Entorno

Crea un archivo `.env.local` en el directorio `project-base`:

```env
# Base de datos
DATABASE_URL="postgresql://shopsys:shopsys@postgres:5432/shopsys"

# Configuración de la aplicación
APP_ENV=dev
APP_DEBUG=true
APP_SECRET=tu-clave-secreta-aqui

# Configuración de dominios
DOMAIN_URLS="http://localhost:8000"
```

### Configuración de Docker

El archivo `docker-compose.yml` está configurado con los servicios esenciales:
- **postgres** - Base de datos PostgreSQL
- **redis** - Cache y sesiones
- **rabbitmq** - Cola de mensajes
- **php-fpm** - Servidor PHP
- **webserver** - Servidor web Nginx

## 🗄️ Migraciones de Base de Datos

El proyecto incluye migraciones personalizadas para agregar campos y tablas al sistema base de Shopsys.

### Migraciones Incluidas

#### 1. **Version20250916000001** - Campos Personalizados de Productos
```sql
-- Agrega campos personalizados a la tabla product
ALTER TABLE product ADD sku VARCHAR(255) DEFAULT NULL;
ALTER TABLE product ADD weight NUMERIC(10, 2) DEFAULT NULL;
ALTER TABLE product ADD dimensions VARCHAR(255) DEFAULT NULL;
ALTER TABLE product ADD manufacturer VARCHAR(255) DEFAULT NULL;
ALTER TABLE product ADD warranty_period VARCHAR(255) DEFAULT NULL;
ALTER TABLE product ADD is_featured BOOLEAN DEFAULT FALSE;
ALTER TABLE product ADD meta_title VARCHAR(255) DEFAULT NULL;
ALTER TABLE product ADD meta_description TEXT DEFAULT NULL;
ALTER TABLE product ADD meta_keywords TEXT DEFAULT NULL;
ALTER TABLE product ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL;
ALTER TABLE product ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL;
```

#### 2. **Version20250916000002** - Categorías Personalizadas
```sql
-- Crea tabla de categorías personalizadas
CREATE TABLE custom_product_category (
    id INT NOT NULL,
    parent_id INT DEFAULT NULL,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    description TEXT DEFAULT NULL,
    is_active BOOLEAN DEFAULT TRUE NOT NULL,
    position INT DEFAULT 0 NOT NULL,
    meta_title VARCHAR(255) DEFAULT NULL,
    meta_description TEXT DEFAULT NULL,
    meta_keywords TEXT DEFAULT NULL,
    created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
    updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
    PRIMARY KEY(id)
);

-- Crea tabla de relación muchos a muchos
CREATE TABLE product_custom_category (
    product_id INT NOT NULL,
    custom_product_category_id INT NOT NULL,
    PRIMARY KEY(product_id, custom_product_category_id)
);
```

### Ejecutar Migraciones

```bash
# Ejecutar migraciones
docker compose exec php-fpm php bin/console doctrine:migrations:migrate

# Ver estado de migraciones
docker compose exec php-fpm php bin/console doctrine:migrations:status

# Generar nueva migración
docker compose exec php-fpm php bin/console doctrine:migrations:generate
```

## 🛠️ Sistema CRUD

### Estructura del CRUD

El sistema CRUD incluye:

#### **Entidades**
- `Product` - Entidad base de Shopsys extendida
- `CustomProductCategory` - Categorías personalizadas

#### **Controladores**
- `ProductController` - Gestión de productos
- `CustomProductCategoryController` - Gestión de categorías

#### **Formularios**
- `ProductType` - Formulario de productos
- `CustomProductCategoryType` - Formulario de categorías

#### **Plantillas**
- `admin/product/` - Plantillas para productos
- `admin/category/` - Plantillas para categorías

### Funcionalidades del CRUD

#### **Productos**
- ✅ **Crear** productos con campos personalizados
- ✅ **Listar** productos con paginación
- ✅ **Ver** detalles de productos
- ✅ **Editar** productos existentes
- ✅ **Eliminar** productos
- ✅ **Marcar** como destacado
- ✅ **Asignar** categorías personalizadas

#### **Categorías**
- ✅ **Crear** categorías con jerarquía
- ✅ **Listar** categorías ordenadas
- ✅ **Ver** detalles de categorías
- ✅ **Editar** categorías existentes
- ✅ **Eliminar** categorías
- ✅ **Activar/Desactivar** categorías
- ✅ **Reordenar** categorías

### Campos Personalizados de Productos

- **SKU** - Código único del producto
- **Peso** - Peso en kilogramos
- **Dimensiones** - Largo x Ancho x Alto
- **Fabricante** - Marca o fabricante
- **Período de Garantía** - Tiempo de garantía
- **Destacado** - Producto destacado (checkbox)
- **Meta Título** - SEO title
- **Meta Descripción** - SEO description
- **Meta Keywords** - SEO keywords
- **Fechas** - Created at / Updated at

## 🎯 Uso del Sistema

### Acceder al Sistema

1. **Frontend**: http://localhost:8000
2. **Admin Panel**: http://localhost:8000/admin

### Gestión de Productos

1. **Acceder a Productos**:
   - Ve a `/admin/products`
   - Lista todos los productos

2. **Crear Producto**:
   - Click en "Nuevo Producto"
   - Completa el formulario
   - Asigna categorías personalizadas
   - Guarda el producto

3. **Editar Producto**:
   - Click en el producto a editar
   - Modifica los campos necesarios
   - Guarda los cambios

4. **Eliminar Producto**:
   - Click en "Eliminar"
   - Confirma la acción

### Gestión de Categorías

1. **Acceder a Categorías**:
   - Ve a `/admin/categories`
   - Lista todas las categorías

2. **Crear Categoría**:
   - Click en "Nueva Categoría"
   - Completa el formulario
   - Asigna categoría padre (opcional)
   - Guarda la categoría

3. **Jerarquía de Categorías**:
   - Las categorías pueden tener categorías padre
   - Soporte para múltiples niveles
   - Ordenamiento por posición

## 📁 Estructura del Proyecto

```
ecommerce-php/
├── project-base/                    # Submodule de Shopsys
│   ├── app/                        # Aplicación principal
│   ├── src/                        # Código fuente personalizado
│   │   ├── Controller/Admin/       # Controladores del admin
│   │   ├── Entity/                 # Entidades personalizadas
│   │   ├── Form/Admin/             # Formularios del admin
│   │   ├── Repository/             # Repositorios
│   │   └── Migrations/             # Migraciones de BD
│   ├── templates/                  # Plantillas personalizadas
│   │   └── admin/                  # Plantillas del admin
│   ├── config/                     # Configuración
│   ├── docker/                     # Configuración Docker
│   └── docker-compose.yml          # Servicios Docker
├── src/                            # Tu código CRUD
├── templates/                      # Tus plantillas
└── README.md                       # Este archivo
```

## 🔧 Desarrollo

### Comandos Útiles

```bash
# Ver logs de servicios
docker compose logs -f php-fpm
docker compose logs -f postgres

# Acceder al contenedor PHP
docker compose exec php-fpm bash

# Limpiar cache
docker compose exec php-fpm php bin/console cache:clear

# Regenerar autoloader
docker compose exec php-fpm composer dump-autoload

# Ejecutar tests
docker compose exec php-fpm php bin/phpunit
```

### Agregar Nuevas Funcionalidades

1. **Crear Entidad**:
   ```bash
   docker compose exec php-fpm php bin/console make:entity
   ```

2. **Crear Controlador**:
   ```bash
   docker compose exec php-fpm php bin/console make:controller
   ```

3. **Crear Formulario**:
   ```bash
   docker compose exec php-fpm php bin/console make:form
   ```

4. **Generar Migración**:
   ```bash
   docker compose exec php-fpm php bin/console doctrine:migrations:generate
   ```

### Personalización

- **Entidades**: Extiende las entidades base de Shopsys
- **Formularios**: Crea formularios personalizados
- **Plantillas**: Modifica las plantillas Twig
- **Rutas**: Agrega nuevas rutas en `config/routes/`

## 🐛 Solución de Problemas

### Problemas Comunes

#### **Error de Permisos**
```bash
# Solucionar permisos
sudo chown -R $USER:$USER .
chmod -R 755 var/
```

#### **Error de Base de Datos**
```bash
# Reiniciar base de datos
docker compose down
docker compose up -d postgres
```

#### **Error de Cache**
```bash
# Limpiar cache
docker compose exec php-fpm php bin/console cache:clear --env=prod
```

#### **Error de Assets**
```bash
# Recompilar assets
docker compose exec php-fpm npm run build
```

### Logs y Debugging

```bash
# Ver logs de PHP
docker compose exec php-fpm tail -f var/log/dev.log

# Ver logs de Nginx
docker compose logs webserver

# Ver logs de PostgreSQL
docker compose logs postgres
```

### Reiniciar Servicios

```bash
# Reiniciar todos los servicios
docker compose restart

# Reiniciar servicio específico
docker compose restart php-fpm
```

## 📞 Soporte

Si encuentras problemas:

1. **Revisa los logs** de los servicios
2. **Verifica la configuración** de Docker
3. **Consulta la documentación** de Shopsys
4. **Revisa los issues** del repositorio

## 📄 Licencia

Este proyecto está basado en Shopsys Platform y sigue su licencia.

---

## 🎉 ¡Disfruta desarrollando!

Este sistema te proporciona una base sólida para crear un ecommerce completo con funcionalidades personalizadas. ¡Explora, modifica y construye algo increíble! 🚀
