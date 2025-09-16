<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250916000001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add custom fields to products table for enhanced product management';
    }

    public function up(Schema $schema): void
    {
        // Agregar campos personalizados a la tabla de productos
        $this->addSql('ALTER TABLE products ADD COLUMN sku VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE products ADD COLUMN weight DECIMAL(10,3) DEFAULT NULL');
        $this->addSql('ALTER TABLE products ADD COLUMN dimensions VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE products ADD COLUMN manufacturer VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE products ADD COLUMN warranty_period INTEGER DEFAULT NULL');
        $this->addSql('ALTER TABLE products ADD COLUMN is_featured BOOLEAN DEFAULT FALSE');
        $this->addSql('ALTER TABLE products ADD COLUMN meta_keywords TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE products ADD COLUMN meta_description TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE products ADD COLUMN created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE products ADD COLUMN updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP');
        
        // Crear índices para mejorar el rendimiento
        $this->addSql('CREATE INDEX IDX_PRODUCTS_SKU ON products (sku)');
        $this->addSql('CREATE INDEX IDX_PRODUCTS_FEATURED ON products (is_featured)');
        $this->addSql('CREATE INDEX IDX_PRODUCTS_MANUFACTURER ON products (manufacturer)');
        $this->addSql('CREATE INDEX IDX_PRODUCTS_CREATED_AT ON products (created_at)');
    }

    public function down(Schema $schema): void
    {
        // Eliminar índices
        $this->addSql('DROP INDEX IDX_PRODUCTS_SKU');
        $this->addSql('DROP INDEX IDX_PRODUCTS_FEATURED');
        $this->addSql('DROP INDEX IDX_PRODUCTS_MANUFACTURER');
        $this->addSql('DROP INDEX IDX_PRODUCTS_CREATED_AT');
        
        // Eliminar campos personalizados
        $this->addSql('ALTER TABLE products DROP COLUMN sku');
        $this->addSql('ALTER TABLE products DROP COLUMN weight');
        $this->addSql('ALTER TABLE products DROP COLUMN dimensions');
        $this->addSql('ALTER TABLE products DROP COLUMN manufacturer');
        $this->addSql('ALTER TABLE products DROP COLUMN warranty_period');
        $this->addSql('ALTER TABLE products DROP COLUMN is_featured');
        $this->addSql('ALTER TABLE products DROP COLUMN meta_keywords');
        $this->addSql('ALTER TABLE products DROP COLUMN meta_description');
        $this->addSql('ALTER TABLE products DROP COLUMN created_at');
        $this->addSql('ALTER TABLE products DROP COLUMN updated_at');
    }
}
