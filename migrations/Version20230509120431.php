<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230509120431 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE car ADD thumbnail_url VARCHAR(255) DEFAULT NULL');
        $this->addSql('UPDATE car SET thumbnail_url = "BMW_2er_Active_Tourer.png" WHERE id=1 ');
        $this->addSql('UPDATE car SET thumbnail_url = "BMW_2er_Gran_Tourer.png" WHERE id=2 ');
        $this->addSql('UPDATE car SET thumbnail_url = "BMW_3er.png" WHERE id=3 ');
        $this->addSql('UPDATE car SET thumbnail_url = "BMW_3er_Touring.png" WHERE id=4 ');
        $this->addSql('UPDATE car SET thumbnail_url = "BMW_5er_Automatik.png" WHERE id=5 ');
        $this->addSql('UPDATE car SET thumbnail_url = "BMW_5er_Touring_Automatik.png" WHERE id=6 ');
        $this->addSql('UPDATE car SET thumbnail_url = "BMW_X3.png" WHERE id=7');
        $this->addSql('UPDATE car SET thumbnail_url = "Fiat_Panda.png" WHERE id=8');
        $this->addSql('UPDATE car SET thumbnail_url = "Ford_C_Max.png" WHERE id=9');
        $this->addSql('UPDATE car SET thumbnail_url = "Ford_Edge.png" WHERE id=10');
        $this->addSql('UPDATE car SET thumbnail_url = "Ford_Fiesta.png" WHERE id=11');
        $this->addSql('UPDATE car SET thumbnail_url = "Ford_Focus_Kombi.png" WHERE id=12');
        $this->addSql('UPDATE car SET thumbnail_url = "Ford_Grand_C_Max.png" WHERE id=13');
        $this->addSql('UPDATE car SET thumbnail_url = "Ford_Kuga.png" WHERE id=14');
        $this->addSql('UPDATE car SET thumbnail_url = "Ford_S_Max.png" WHERE id=15');
        $this->addSql('UPDATE car SET thumbnail_url = "Ford_Tourneo_Connect.png" WHERE id=16');
        $this->addSql('UPDATE car SET thumbnail_url = "Ford_Tourneo_Custom.png" WHERE id=17');
        $this->addSql('UPDATE car SET thumbnail_url = "Mercedes_Benz_Sprinter.png" WHERE id=18');
        $this->addSql('UPDATE car SET thumbnail_url = "Opel_Astra.png" WHERE id=19');
        $this->addSql('UPDATE car SET thumbnail_url = "Skoda_Octavia_Kombi.png" WHERE id=20');
        $this->addSql('UPDATE car SET thumbnail_url = "Skoda_Yeti.png" WHERE id=21');
        $this->addSql('UPDATE car SET thumbnail_url = "VW_Golf.png" WHERE id=22');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE car DROP thumbnail_url');
    }
}
