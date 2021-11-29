<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211129125614 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE project_entry (id INT AUTO_INCREMENT NOT NULL, submission_id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, target_hours DOUBLE PRECISION NOT NULL, actual_hours DOUBLE PRECISION DEFAULT NULL, priority VARCHAR(255) NOT NULL, work_results LONGTEXT DEFAULT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_387494EEE1FD4933 (submission_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE project_entry ADD CONSTRAINT FK_387494EEE1FD4933 FOREIGN KEY (submission_id) REFERENCES submission (id)');
        $this->addSql('DROP TABLE project');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE project (id INT AUTO_INCREMENT NOT NULL, submission_id_id INT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, target_hours DOUBLE PRECISION NOT NULL, actual_hours DOUBLE PRECISION DEFAULT NULL, priority VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, work_results LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, status VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_2FB3D0EEA2DBE4F5 (submission_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EEA2DBE4F5 FOREIGN KEY (submission_id_id) REFERENCES submission (id)');
        $this->addSql('DROP TABLE project_entry');
    }
}
