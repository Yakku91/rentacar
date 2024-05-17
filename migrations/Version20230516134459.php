<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230516134459 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'create car-table and insert car-pool';
    }

    public function up(Schema $schema): void
    {   // Dummy User
        $this->addSql("INSERT INTO user (email, roles, password, form_of_address, first_name, last_name, address, preferred_method_of_payment, phone_number) VALUES
	            ('deleted-user@mobilmacher.de', '[\"ROLE_USER\"]', '$2y$13\$bLdDH/28ylDVRJ7skcMvSezZg.ITvH0Oo0y0MnfdKUaAtPz4LyH6W', 'Deleted-User', 'Deleted-User', 'Deleted-User', 'Deleted-User', 'Deleted-User', 'Deleted-User')
	            ");
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT INTO user (email, roles, password, form_of_address, first_name, last_name, address, preferred_method_of_payment, phone_number) VALUES
	            ('admin@mobilmacher.de', '[\"ROLE_ADMIN\"]', '$2y$13\$bLdDH/28ylDVRJ7skcMvSezZg.ITvH0Oo0y0MnfdKUaAtPz4LyH6W', 'male', 'Tom', 'Tomsen', 'Schuppen Eins', 'Bar', '1234567')
	            ");
    }

}
