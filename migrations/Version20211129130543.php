<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211129130543 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE miscellaneous_entry (id INT AUTO_INCREMENT NOT NULL, submission_id_id INT NOT NULL, task VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, target_hours DOUBLE PRECISION NOT NULL, comment LONGTEXT DEFAULT NULL, INDEX IDX_D1B2A08FA2DBE4F5 (submission_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE operation_entry (id INT AUTO_INCREMENT NOT NULL, submission_id INT NOT NULL, category VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, hours DOUBLE PRECISION NOT NULL, priority VARCHAR(255) NOT NULL, work_results LONGTEXT DEFAULT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_AC064EFFE1FD4933 (submission_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE miscellaneous_entry ADD CONSTRAINT FK_D1B2A08FA2DBE4F5 FOREIGN KEY (submission_id_id) REFERENCES submission (id)');
        $this->addSql('ALTER TABLE operation_entry ADD CONSTRAINT FK_AC064EFFE1FD4933 FOREIGN KEY (submission_id) REFERENCES submission (id)');
        $this->addSql('DROP TABLE miscellaneous');
        $this->addSql('DROP TABLE operation');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE miscellaneous (id INT AUTO_INCREMENT NOT NULL, submission_id_id INT NOT NULL, task VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, target_hours DOUBLE PRECISION NOT NULL, comment LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_427B45FEA2DBE4F5 (submission_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE operation (id INT AUTO_INCREMENT NOT NULL, submission_id_id INT NOT NULL, category VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, hours DOUBLE PRECISION NOT NULL, priority VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, work_results LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, status VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_1981A66DA2DBE4F5 (submission_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE miscellaneous ADD CONSTRAINT FK_427B45FEA2DBE4F5 FOREIGN KEY (submission_id_id) REFERENCES submission (id)');
        $this->addSql('ALTER TABLE operation ADD CONSTRAINT FK_1981A66DA2DBE4F5 FOREIGN KEY (submission_id_id) REFERENCES submission (id)');
        $this->addSql('DROP TABLE miscellaneous_entry');
        $this->addSql('DROP TABLE operation_entry');
    }
}
