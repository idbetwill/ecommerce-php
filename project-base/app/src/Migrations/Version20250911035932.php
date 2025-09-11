<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250911035932 extends AbstractMigration
{
    public function getDescription(): string
    {
        return "Create custom_products table for custom product management";
    }

    public function up(Schema $schema): void
    {
        $this->addSql("CREATE TABLE custom_products (
            id SERIAL PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            description TEXT,
            price DECIMAL(10,2) NOT NULL,
            sku VARCHAR(100) UNIQUE,
            is_active BOOLEAN DEFAULT true,
            created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
        )");
        
        $this->addSql("CREATE INDEX idx_custom_products_sku ON custom_products (sku)");
        $this->addSql("CREATE INDEX idx_custom_products_active ON custom_products (is_active)");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DROP TABLE custom_products");
    }
}