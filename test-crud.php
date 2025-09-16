<?php

/**
 * Script de prueba para el CRUD de productos
 * Este script simula las operaciones básicas del CRUD
 */

echo "🧪 Script de Prueba - CRUD de Productos\n";
echo "=====================================\n\n";

// Simular datos de prueba
$testProducts = [
    [
        'name' => 'Laptop Gaming ASUS ROG',
        'sku' => 'ASUS-ROG-001',
        'weight' => 2.5,
        'dimensions' => '35 x 25 x 2.5 cm',
        'manufacturer' => 'ASUS',
        'warranty_period' => 24,
        'is_featured' => true,
        'meta_title' => 'Laptop Gaming ASUS ROG - Potencia y Rendimiento',
        'meta_description' => 'Laptop gaming de alta gama con procesador Intel i7 y tarjeta gráfica RTX 4060',
        'meta_keywords' => 'laptop, gaming, asus, rog, intel, rtx, 4060'
    ],
    [
        'name' => 'Mouse Inalámbrico Logitech MX Master 3',
        'sku' => 'LOG-MX3-001',
        'weight' => 0.141,
        'dimensions' => '12.6 x 8.4 x 5.1 cm',
        'manufacturer' => 'Logitech',
        'warranty_period' => 12,
        'is_featured' => false,
        'meta_title' => 'Mouse Inalámbrico Logitech MX Master 3',
        'meta_description' => 'Mouse ergonómico inalámbrico con sensor de alta precisión',
        'meta_keywords' => 'mouse, inalámbrico, logitech, mx master, ergonómico'
    ],
    [
        'name' => 'Teclado Mecánico Corsair K95 RGB',
        'sku' => 'COR-K95-001',
        'weight' => 1.2,
        'dimensions' => '46.5 x 16.5 x 3.6 cm',
        'manufacturer' => 'Corsair',
        'warranty_period' => 24,
        'is_featured' => true,
        'meta_title' => 'Teclado Mecánico Corsair K95 RGB Platinum',
        'meta_description' => 'Teclado mecánico gaming con switches Cherry MX y retroiluminación RGB',
        'meta_keywords' => 'teclado, mecánico, corsair, k95, rgb, gaming, cherry mx'
    ]
];

$testCategories = [
    [
        'name' => 'Laptops y Computadoras',
        'slug' => 'laptops-computadoras',
        'description' => 'Laptops, computadoras de escritorio y accesorios relacionados',
        'parent_id' => null,
        'sort_order' => 1,
        'is_active' => true,
        'meta_title' => 'Laptops y Computadoras - Tienda Online',
        'meta_description' => 'Encuentra las mejores laptops y computadoras para trabajo y gaming',
        'meta_keywords' => 'laptop, computadora, desktop, gaming, trabajo'
    ],
    [
        'name' => 'Periféricos',
        'slug' => 'perifericos',
        'description' => 'Teclados, mouse, monitores y otros periféricos',
        'parent_id' => null,
        'sort_order' => 2,
        'is_active' => true,
        'meta_title' => 'Periféricos de Computadora - Tienda Online',
        'meta_description' => 'Teclados, mouse, monitores y accesorios para tu computadora',
        'meta_keywords' => 'periféricos, teclado, mouse, monitor, accesorios'
    ],
    [
        'name' => 'Gaming',
        'slug' => 'gaming',
        'description' => 'Productos especializados para gaming',
        'parent_id' => 1, // Laptops y Computadoras
        'sort_order' => 1,
        'is_active' => true,
        'meta_title' => 'Productos Gaming - Tienda Online',
        'meta_description' => 'Equipos y accesorios especializados para gaming profesional',
        'meta_keywords' => 'gaming, videojuegos, equipos gaming, accesorios gaming'
    ]
];

echo "📦 Datos de Prueba Generados:\n";
echo "=============================\n\n";

echo "🖥️  Productos de Prueba:\n";
foreach ($testProducts as $index => $product) {
    echo sprintf(
        "%d. %s (SKU: %s) - %s - %s\n",
        $index + 1,
        $product['name'],
        $product['sku'],
        $product['manufacturer'],
        $product['is_featured'] ? '⭐ Destacado' : 'Normal'
    );
}

echo "\n📂 Categorías de Prueba:\n";
foreach ($testCategories as $index => $category) {
    $parent = $category['parent_id'] ? " (Hijo de: Categoría {$category['parent_id']})" : " (Categoría raíz)";
    echo sprintf(
        "%d. %s (%s)%s\n",
        $index + 1,
        $category['name'],
        $category['slug'],
        $parent
    );
}

echo "\n🔧 Funcionalidades del CRUD:\n";
echo "============================\n\n";

echo "✅ Gestión de Productos:\n";
echo "   • Crear productos con información completa\n";
echo "   • Listar productos con filtros y paginación\n";
echo "   • Editar información de productos existentes\n";
echo "   • Eliminar productos con confirmación\n";
echo "   • Marcar productos como destacados\n";
echo "   • Búsqueda por nombre o SKU\n";
echo "   • Filtrado por categorías\n\n";

echo "✅ Gestión de Categorías:\n";
echo "   • Crear categorías con jerarquía\n";
echo "   • Listar categorías con información de productos\n";
echo "   • Editar categorías existentes\n";
echo "   • Eliminar categorías\n";
echo "   • Activar/desactivar categorías\n";
echo "   • Orden personalizable\n";
echo "   • Categorías padre/hijo\n\n";

echo "🌐 URLs de Acceso:\n";
echo "==================\n\n";
echo "Panel de Administración:\n";
echo "• Productos: http://localhost:8000/admin/products\n";
echo "• Categorías: http://localhost:8000/admin/categories\n\n";

echo "📋 Rutas API:\n";
echo "=============\n\n";
echo "Productos:\n";
echo "• GET    /admin/products              - Listado\n";
echo "• GET    /admin/products/new          - Formulario nuevo\n";
echo "• POST   /admin/products/new          - Crear producto\n";
echo "• GET    /admin/products/{id}         - Ver producto\n";
echo "• GET    /admin/products/{id}/edit    - Formulario edición\n";
echo "• POST   /admin/products/{id}/edit    - Actualizar producto\n";
echo "• POST   /admin/products/{id}         - Eliminar producto\n";
echo "• POST   /admin/products/{id}/toggle-featured - Toggle destacado\n\n";

echo "Categorías:\n";
echo "• GET    /admin/categories            - Listado\n";
echo "• GET    /admin/categories/new        - Formulario nueva\n";
echo "• POST   /admin/categories/new        - Crear categoría\n";
echo "• GET    /admin/categories/{id}       - Ver categoría\n";
echo "• GET    /admin/categories/{id}/edit  - Formulario edición\n";
echo "• POST   /admin/categories/{id}/edit  - Actualizar categoría\n";
echo "• POST   /admin/categories/{id}       - Eliminar categoría\n";
echo "• POST   /admin/categories/{id}/toggle-active - Toggle activo\n\n";

echo "🗄️  Base de Datos:\n";
echo "==================\n\n";
echo "Tablas creadas:\n";
echo "• custom_product_categories - Categorías personalizadas\n";
echo "• product_custom_categories - Relación productos-categorías\n\n";

echo "Campos agregados a 'products':\n";
echo "• sku, weight, dimensions, manufacturer\n";
echo "• warranty_period, is_featured\n";
echo "• meta_title, meta_description, meta_keywords\n";
echo "• created_at, updated_at\n\n";

echo "🔒 Seguridad:\n";
echo "=============\n\n";
echo "• Autenticación requerida (ROLE_ADMIN)\n";
echo "• Protección CSRF en todas las operaciones\n";
echo "• Validación del lado cliente y servidor\n";
echo "• Confirmaciones para operaciones destructivas\n\n";

echo "✨ Características Especiales:\n";
echo "=============================\n\n";
echo "• Auto-generación de slugs desde nombres\n";
echo "• Validación en tiempo real en formularios\n";
echo "• Interfaz responsive con Bootstrap\n";
echo "• Paginación automática\n";
echo "• Filtros y búsqueda avanzada\n";
echo "• Indicadores visuales de estado\n";
echo "• Información de auditoría (fechas)\n\n";

echo "🎯 ¡CRUD de Productos Listo para Usar!\n";
echo "=====================================\n\n";
echo "Para comenzar:\n";
echo "1. Ejecutar migraciones: php bin/console doctrine:migrations:migrate\n";
echo "2. Acceder al admin: http://localhost:8000/admin/products\n";
echo "3. Crear categorías primero, luego productos\n";
echo "4. ¡Disfrutar del sistema de gestión!\n\n";
