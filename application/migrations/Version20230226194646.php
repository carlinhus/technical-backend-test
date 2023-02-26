<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230226194646 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Erases shorted_url table and inserts two example urls';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DELETE FROM shorted_url');
        $this->addSql('INSERT INTO shorted_url (id, destiny, origin_url) VALUES (1, \'https://tinyurl.com/yc53pdo\', \'https://www.google.es\'), (2, \'https://tinyurl.com/2h7x4l3e\', \'https://github.com/carlinhus\')');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DELETE FROM shorted_url where id in (1,2)');
    }
}
