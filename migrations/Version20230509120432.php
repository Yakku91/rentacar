<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230509120432 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'added missing img';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('UPDATE car SET thumbnail_url = "BMW_2er_Gran_Tourer.png" WHERE id=2 ');
        $this->addSql('UPDATE car SET thumbnail_url = "BMW_2er_Active_Tourer.png" WHERE id=1 ');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}
