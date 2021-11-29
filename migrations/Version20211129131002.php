<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211129131002 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE miscellaneous_entry DROP FOREIGN KEY FK_D1B2A08FA2DBE4F5');
        $this->addSql('DROP INDEX IDX_D1B2A08FA2DBE4F5 ON miscellaneous_entry');
        $this->addSql('ALTER TABLE miscellaneous_entry CHANGE submission_id_id submission_id INT NOT NULL');
        $this->addSql('ALTER TABLE miscellaneous_entry ADD CONSTRAINT FK_D1B2A08FE1FD4933 FOREIGN KEY (submission_id) REFERENCES submission (id)');
        $this->addSql('CREATE INDEX IDX_D1B2A08FE1FD4933 ON miscellaneous_entry (submission_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE miscellaneous_entry DROP FOREIGN KEY FK_D1B2A08FE1FD4933');
        $this->addSql('DROP INDEX IDX_D1B2A08FE1FD4933 ON miscellaneous_entry');
        $this->addSql('ALTER TABLE miscellaneous_entry CHANGE submission_id submission_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE miscellaneous_entry ADD CONSTRAINT FK_D1B2A08FA2DBE4F5 FOREIGN KEY (submission_id_id) REFERENCES submission (id)');
        $this->addSql('CREATE INDEX IDX_D1B2A08FA2DBE4F5 ON miscellaneous_entry (submission_id_id)');
    }
}
