<?php
declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230512 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'create car-table and insert car-pool';
    }
    public function up(Schema $schema): void
    {
        $this->addSql('update car set price_per_week = 1050 where id = 5');
        $this->addSql('update car set price_per_week = 1240 where id = 6');
        $this->addSql('update car set price_per_week = 1300 where id = 10');
    }
}