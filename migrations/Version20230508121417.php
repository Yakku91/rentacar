<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230508121417 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'create car-table and insert car-pool';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE car (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, category VARCHAR(255) NOT NULL, seats INT NOT NULL, luggage INT NOT NULL, doors INT NOT NULL, gear VARCHAR(255) NOT NULL, included_kilometres INT NOT NULL, price_per_day DOUBLE PRECISION NOT NULL, price_per_weekend DOUBLE PRECISION NOT NULL, price_per_week DOUBLE PRECISION NOT NULL, price_per_kilometre DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('INSERT INTO car (name, category, seats, luggage, doors, gear, included_kilometres, price_per_day, price_per_weekend, price_per_week, price_per_kilometre) VALUES
	            ("BMW 2er Active Tourer", "Limousine", 5, 2, 4, "Manuell", 500, 29.1, 72, 175, 0.35),
	            ("BMW 2er Gran Tourer", "Kombi", 5, 3, 4, "Manuell", 500, 19.7, 48, 120, 0.35),
	            ("BMW 3er", "Limousine", 5, 2, 4, "Manuell", 1000, 120.1, 299, 720, 0.6),
	            ("BMW 3er Touring", "Kombi", 5, 3, 4, "Manuell", 1000, 132.1, 329, 795, 0.6),
	            ("BMW 5er Automatik", "Limousine", 5, 2, 4, "Automatik", 1000, 171.7, 425, 1050, 0.7),
	            ("BMW 5er Touring Automatik", "Kombi", 5, 3, 4, "Automatik", 1000, 206.1, 499, 1240, 0.7),
	            ("BMW X3", "Geländewagen", 5, 4, 4, "Manuell", 700, 44.6, 111, 275, 0.4),
	            ("Fiat Panda", "Limousine", 4, 1, 2, "Manuell", 500, 19.99, 49, 120, 0.35),
	            ("Ford C-Max", "Limousine", 5, 2, 4, "Manuell", 500, 28.79, 71, 175, 0.35),
	            ("Ford Edge", "Geländewagen", 5, 4, 4, "Automatik", 1000, 212.29, 525, 1300, 0.7),
                ("Ford Fiesta", "Limousine", 4, 1, 4, "Manuell", 500, 20.58, 49, 125, 0.35),
	            ("Ford Focus Kombi", "Kombi", 5, 2, 4, "Manuell", 500, 18.74, 45, 115, 0.35),
	            ("Ford Grand C-Max", "Minibus", 7, 2, 4, "Manuell", 700, 51.2, 128, 310, 0.4),
	            ("Ford Kuga", "Geländewagen", 5, 4, 4, "Manuell", 700, 38.8, 95, 235, 0.4),
	            ("Ford S-Max", "Minibus", 7, 2, 4, "Manuell", 700, 52.25, 128, 320, 0.4),
	            ("Ford Tourneo Connect", "Minibus", 7, 2, 4, "Manuell", 700, 85.06, 211, 510, 0.5),
	            ("Ford Tourneo Custom", "Minibus", 9, 4, 4, "Manuell", 700, 87.7, 215, 530, 0.5),
	            ("Mercedes-Benz Sprinter (9-Sitzer)", "Minibus", 9, 4, 4, "Manuell", 1000, 105.2, 255, 640, 0.6),
	            ("Opel Astra", "Limousine", 5, 2, 4, "Manuell", 500, 25.78, 61, 155, 0.35),
	            ("Skoda Octavia Kombi", "Kombi", 5, 2, 4, "Manuell", 500, 19.4, 47, 120, 0.35),
	            ("Skoda Yeti", "Geländewagen", 5, 4, 4, "Manuell", 500, 29.68, 72, 178.08, 0.35),
	            ("VW Golf", "Limousine", 5, 2, 4, "Manuell", 500, 28.5, 69, 175, 0.35);');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE car');
    }
}
