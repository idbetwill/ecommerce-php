<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250916000002 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create custom product categories table';
    }

    public function up(Schema $schema): void
    {
        // Crear tabla de categorías personalizadas
        $this->addSql('CREATE TABLE custom_product_categories (
            id SERIAL PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            slug VARCHAR(255) NOT NULL,
            description TEXT DEFAULT NULL,
            parent_id INTEGER DEFAULT NULL,
            sort_order INTEGER DEFAULT 0,
            is_active BOOLEAN DEFAULT TRUE,
            meta_title VARCHAR(255) DEFAULT NULL,
            meta_description TEXT DEFAULT NULL,
            meta_keywords TEXT DEFAULT NULL,
            created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
            CONSTRAINT FK_CUSTOM_CATEGORIES_PARENT FOREIGN KEY (parent_id) REFERENCES custom_product_categories (id) ON DELETE SET NULL
        )');
        
        // Crear tabla de relación entre productos y categorías personalizadas
        $this->addSql('CREATE TABLE product_custom_categories (
            product_id INTEGER NOT NULL,
            category_id INTEGER NOT NULL,
            PRIMARY KEY (product_id, category_id),
            CONSTRAINT FK_PRODUCT_CUSTOM_CATEGORIES_PRODUCT FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE,
            CONSTRAINT FK_PRODUCT_CUSTOM_CATEGORIES_CATEGORY FOREIGN KEY (category_id) REFERENCES custom_product_categories (id) ON DELETE CASCADE
        )');
        
        // Crear índices
        $this->addSql('CREATE INDEX IDX_CUSTOM_CATEGORIES_SLUG ON custom_product_categories (slug)');
        $this->addSql('CREATE INDEX IDX_CUSTOM_CATEGORIES_ACTIVE ON custom_product_categories (is_active)');
        $this->addSql('CREATE INDEX IDX_CUSTOM_CATEGORIES_PARENT ON custom_product_categories (parent_id)');
        $this->addSql('CREATE INDEX IDX_CUSTOM_CATEGORIES_SORT ON custom_product_categories (sort_order)');
    }

    public function down(Schema $schema): void
    {
        // Eliminar tabla de relación
        $this->addSql('DROP TABLE product_custom_categories');
        
        // Eliminar tabla de categorías personalizadas
        $this->addSql('DROP TABLE custom_product_categories');
    }
}
