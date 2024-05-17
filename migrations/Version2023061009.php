<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version2023061009 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('UPDATE car SET child_seat = 3 WHERE seats = 4');
        $this->addSql('UPDATE car SET child_seat = 4 WHERE seats = 5');
        $this->addSql('UPDATE car SET child_seat = 6 WHERE seats = 7');
        $this->addSql('UPDATE car SET child_seat = 8 WHERE seats = 9');
        $this->addSql('UPDATE car SET is_doge_cage_compatible = 0, child_seat = 0 where name = "Fiat Panda"');
        $this->addSql('UPDATE car SET is_doge_cage_compatible = 1 where name != "Fiat Panda"');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE car DROP is_doge_cage_compatible');
    }

}
