# CRUD de Productos - Shopsys Platform

Este proyecto incluye un sistema completo de gestión de productos (CRUD) con categorías personalizadas para Shopsys Platform.

## 🚀 Características

### Gestión de Productos
- ✅ **Crear** productos con información detallada
- ✅ **Leer** listado de productos con filtros y paginación
- ✅ **Actualizar** información de productos existentes
- ✅ **Eliminar** productos con confirmación
- ✅ **Marcar como destacado** productos especiales
- ✅ **Búsqueda** por nombre o SKU
- ✅ **Filtrado** por categorías

### Gestión de Categorías Personalizadas
- ✅ **Crear** categorías con jerarquía
- ✅ **Leer** listado de categorías
- ✅ **Actualizar** información de categorías
- ✅ **Eliminar** categorías
- ✅ **Activar/Desactivar** categorías
- ✅ **Categorías padre/hijo** para jerarquías
- ✅ **Orden personalizable** de categorías

### Campos de Producto
- **Información Básica:**
  - Nombre del producto
  - SKU (código único)
  - Peso (en kg)
  - Dimensiones (L x W x H)
  - Fabricante
  - Período de garantía (meses)
  - Producto destacado (sí/no)

- **SEO:**
  - Meta título
  - Meta descripción
  - Meta keywords

- **Relaciones:**
  - Categorías personalizadas (muchos a muchos)

### Campos de Categoría
- **Información Básica:**
  - Nombre de la categoría
  - Slug (URL amigable)
  - Descripción
  - Categoría padre (opcional)
  - Orden de clasificación
  - Estado activo/inactivo

- **SEO:**
  - Meta título
  - Meta descripción
  - Meta keywords

## 📁 Estructura de Archivos

```
src/
├── Controller/Admin/
│   ├── ProductController.php              # CRUD de productos
│   └── CustomProductCategoryController.php # CRUD de categorías
├── Entity/
│   ├── CustomProductCategory.php          # Entidad de categorías
│   └── Product.php                        # Entidad de productos (extendida)
├── Form/Admin/
│   ├── ProductType.php                    # Formulario de productos
│   └── CustomProductCategoryType.php      # Formulario de categorías
├── Repository/
│   └── CustomProductCategoryRepository.php # Repositorio de categorías
└── Migrations/
    ├── Version20250916000001.php          # Campos adicionales en productos
    └── Version20250916000002.php          # Tabla de categorías personalizadas

templates/admin/
├── product/
│   ├── index.html.twig                    # Listado de productos
│   ├── new.html.twig                      # Crear producto
│   ├── edit.html.twig                     # Editar producto
│   └── show.html.twig                     # Ver producto
└── category/
    ├── index.html.twig                    # Listado de categorías
    ├── new.html.twig                      # Crear categoría
    ├── edit.html.twig                     # Editar categoría
    └── show.html.twig                     # Ver categoría

config/routes/
└── admin.yaml                             # Rutas del panel de administración
```

## 🛠️ Instalación

### 1. Ejecutar Migraciones
```bash
# Aplicar las migraciones para crear las tablas y campos
docker compose exec php-fpm php bin/console doctrine:migrations:migrate
```

### 2. Acceder al Panel de Administración
- **Productos:** `http://localhost:8000/admin/products`
- **Categorías:** `http://localhost:8000/admin/categories`

## 📋 Rutas Disponibles

### Productos
- `GET /admin/products` - Listado de productos
- `GET /admin/products/new` - Formulario de nuevo producto
- `POST /admin/products/new` - Crear producto
- `GET /admin/products/{id}` - Ver producto
- `GET /admin/products/{id}/edit` - Formulario de edición
- `POST /admin/products/{id}/edit` - Actualizar producto
- `POST /admin/products/{id}` - Eliminar producto
- `POST /admin/products/{id}/toggle-featured` - Toggle destacado

### Categorías
- `GET /admin/categories` - Listado de categorías
- `GET /admin/categories/new` - Formulario de nueva categoría
- `POST /admin/categories/new` - Crear categoría
- `GET /admin/categories/{id}` - Ver categoría
- `GET /admin/categories/{id}/edit` - Formulario de edición
- `POST /admin/categories/{id}/edit` - Actualizar categoría
- `POST /admin/categories/{id}` - Eliminar categoría
- `POST /admin/categories/{id}/toggle-active` - Toggle activo/inactivo

## 🎨 Características de la Interfaz

### Listado de Productos
- Tabla responsive con información clave
- Filtros por búsqueda y categoría
- Paginación automática
- Acciones rápidas (ver, editar, eliminar, destacar)
- Indicadores visuales de estado

### Formularios
- Validación en tiempo real
- Auto-generación de slugs
- Campos organizados por secciones
- Información contextual de ayuda
- Confirmaciones de eliminación

### Detalles
- Vista completa de información
- Estadísticas relacionadas
- Acciones contextuales
- Información de auditoría (fechas de creación/actualización)

## 🔧 Personalización

### Agregar Nuevos Campos
1. Modificar la entidad `Product` o `CustomProductCategory`
2. Actualizar el formulario correspondiente
3. Crear una nueva migración
4. Actualizar las plantillas Twig

### Modificar Validaciones
Editar los constraints en los archivos de formulario:
- `src/Form/Admin/ProductType.php`
- `src/Form/Admin/CustomProductCategoryType.php`

### Personalizar Interfaz
Modificar las plantillas en `templates/admin/` según necesidades.

## 🚨 Notas Importantes

- **Seguridad:** Todas las rutas requieren autenticación de administrador (`ROLE_ADMIN`)
- **Validación:** Los formularios incluyen validación tanto del lado cliente como del servidor
- **CSRF:** Todas las operaciones de modificación incluyen protección CSRF
- **Relaciones:** Las categorías pueden tener jerarquía padre/hijo
- **Auditoría:** Se registran automáticamente las fechas de creación y actualización

## 📊 Base de Datos

### Tablas Creadas
- `custom_product_categories` - Categorías personalizadas
- `product_custom_categories` - Relación muchos a muchos entre productos y categorías

### Campos Agregados a `products`
- `sku` - Código SKU del producto
- `weight` - Peso en kilogramos
- `dimensions` - Dimensiones del producto
- `manufacturer` - Fabricante
- `warranty_period` - Período de garantía en meses
- `is_featured` - Producto destacado
- `meta_title` - Meta título para SEO
- `meta_description` - Meta descripción para SEO
- `meta_keywords` - Meta keywords para SEO
- `created_at` - Fecha de creación
- `updated_at` - Fecha de última actualización

## 🎯 Próximos Pasos

- [ ] Implementar subida de imágenes para productos
- [ ] Agregar sistema de variantes de productos
- [ ] Implementar exportación/importación de datos
- [ ] Agregar más filtros de búsqueda
- [ ] Implementar ordenamiento personalizable
- [ ] Agregar logs de auditoría detallados
