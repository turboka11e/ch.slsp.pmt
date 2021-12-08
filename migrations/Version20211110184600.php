<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211110184600 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE miscellaneous (id INT AUTO_INCREMENT NOT NULL, submission_id_id INT NOT NULL, task VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, target_hours DOUBLE PRECISION NOT NULL, comment LONGTEXT DEFAULT NULL, INDEX IDX_427B45FEA2DBE4F5 (submission_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE operation (id INT AUTO_INCREMENT NOT NULL, submission_id_id INT NOT NULL, category VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, hours DOUBLE PRECISION NOT NULL, priority VARCHAR(255) NOT NULL, work_results LONGTEXT DEFAULT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_1981A66DA2DBE4F5 (submission_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project (id INT AUTO_INCREMENT NOT NULL, submission_id_id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, target_hours DOUBLE PRECISION NOT NULL, actual_hours DOUBLE PRECISION NOT NULL, priority VARCHAR(255) NOT NULL, work_results LONGTEXT DEFAULT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_2FB3D0EEA2DBE4F5 (submission_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE submission (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, created DATE NOT NULL, updated DATE NOT NULL, submission_month DATE NOT NULL, form_type VARCHAR(255) NOT NULL, workdays DOUBLE PRECISION NOT NULL, INDEX IDX_DB055AF39D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE miscellaneous ADD CONSTRAINT FK_427B45FEA2DBE4F5 FOREIGN KEY (submission_id_id) REFERENCES submission (id)');
        $this->addSql('ALTER TABLE operation ADD CONSTRAINT FK_1981A66DA2DBE4F5 FOREIGN KEY (submission_id_id) REFERENCES submission (id)');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EEA2DBE4F5 FOREIGN KEY (submission_id_id) REFERENCES submission (id)');
        $this->addSql('ALTER TABLE submission ADD CONSTRAINT FK_DB055AF39D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE miscellaneous DROP FOREIGN KEY FK_427B45FEA2DBE4F5');
        $this->addSql('ALTER TABLE operation DROP FOREIGN KEY FK_1981A66DA2DBE4F5');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EEA2DBE4F5');
        $this->addSql('DROP TABLE miscellaneous');
        $this->addSql('DROP TABLE operation');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE submission');
    }
}
