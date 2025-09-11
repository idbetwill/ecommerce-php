<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250911035847 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX cart_identifier');
        $this->addSql('DROP INDEX customer_user_id');
        $this->addSql('DROP INDEX product_translations_name_cs_idx');
        $this->addSql('DROP INDEX product_translations_name_en_idx');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE INDEX product_translations_name_cs_idx ON product_translations (name) WHERE ((locale)::text = \':locale\'::text)');
        $this->addSql('CREATE INDEX product_translations_name_en_idx ON product_translations (name) WHERE ((locale)::text = \':locale\'::text)');
        $this->addSql('CREATE UNIQUE INDEX cart_identifier ON carts (cart_identifier) WHERE ((cart_identifier)::text <> \'\'::text)');
        $this->addSql('CREATE UNIQUE INDEX customer_user_id ON carts (customer_user_id)');
    }
}
