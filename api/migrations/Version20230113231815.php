<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230113231815 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE cart (id UUID NOT NULL, user_id UUID DEFAULT NULL, PRIMARY KEY(id));');
        $this->addSql('CREATE INDEX IDX_BA388B7A76ED395 ON cart (user_id);');
        $this->addSql('COMMENT ON COLUMN cart.id IS \'(DC2Type:uuid)\';');
        $this->addSql('COMMENT ON COLUMN cart.user_id IS \'(DC2Type:uuid)\';');
        $this->addSql('CREATE TABLE product (id UUID NOT NULL, name VARCHAR(255) NOT NULL, price INT NOT NULL, PRIMARY KEY(id));');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D34A04AD5E237E06 ON product (name);');
        $this->addSql('COMMENT ON COLUMN product.id IS \'(DC2Type:uuid)\';');
        $this->addSql('CREATE TABLE "user" (id UUID NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id));');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON "user" (username);');
        $this->addSql('CREATE INDEX username_index ON "user" (username);');
        $this->addSql('COMMENT ON COLUMN "user".id IS \'(DC2Type:uuid)\';');
        $this->addSql('CREATE TABLE line_item (id UUID NOT NULL, cart_id UUID DEFAULT NULL, product_id UUID DEFAULT NULL, quantity INT NOT NULL, PRIMARY KEY(id));');
        $this->addSql('CREATE INDEX IDX_9456D6C71AD5CDBF ON line_item (cart_id);');
        $this->addSql('CREATE INDEX IDX_9456D6C74584665A ON line_item (product_id);');
        $this->addSql('COMMENT ON COLUMN line_item.id IS \'(DC2Type:uuid)\';');
        $this->addSql('COMMENT ON COLUMN line_item.cart_id IS \'(DC2Type:uuid)\';');
        $this->addSql('COMMENT ON COLUMN line_item.product_id IS \'(DC2Type:uuid)\';');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B7A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE;');
        $this->addSql('ALTER TABLE line_item ADD CONSTRAINT FK_9456D6C71AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id) NOT DEFERRABLE INITIALLY IMMEDIATE;');
        $this->addSql('ALTER TABLE line_item ADD CONSTRAINT FK_9456D6C74584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE;');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE cart DROP CONSTRAINT FK_BA388B7A76ED395;');
        $this->addSql('ALTER TABLE line_item DROP CONSTRAINT FK_9456D6C71AD5CDBF;');
        $this->addSql('ALTER TABLE line_item DROP CONSTRAINT FK_9456D6C74584665A;');
        $this->addSql('DROP TABLE cart;');
        $this->addSql('DROP TABLE product;');
        $this->addSql('DROP TABLE "user";');
        $this->addSql('DROP TABLE line_item;');
    }
}
