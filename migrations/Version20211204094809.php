<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211204094809 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE submission (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, created DATE NOT NULL, updated DATE NOT NULL, submission_month DATE NOT NULL, form_type VARCHAR(255) NOT NULL, workdays DOUBLE PRECISION NOT NULL, planned_absences DOUBLE PRECISION NOT NULL, further_absences DOUBLE PRECISION NOT NULL, INDEX IDX_DB055AF3A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, name VARCHAR(20) NOT NULL, surname VARCHAR(20) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE submission ADD CONSTRAINT FK_DB055AF3A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE category_choice CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE miscellaneous_entry CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE miscellaneous_entry ADD CONSTRAINT FK_D1B2A08FE1FD4933 FOREIGN KEY (submission_id) REFERENCES submission (id)');
        $this->addSql('ALTER TABLE operation_entry CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE operation_entry ADD CONSTRAINT FK_AC064EFFE1FD4933 FOREIGN KEY (submission_id) REFERENCES submission (id)');
        $this->addSql('ALTER TABLE project CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE project_entry CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE project_entry ADD CONSTRAINT FK_387494EEE1FD4933 FOREIGN KEY (submission_id) REFERENCES submission (id)');
        $this->addSql('ALTER TABLE project_entry ADD CONSTRAINT FK_387494EE166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE miscellaneous_entry DROP FOREIGN KEY FK_D1B2A08FE1FD4933');
        $this->addSql('ALTER TABLE operation_entry DROP FOREIGN KEY FK_AC064EFFE1FD4933');
        $this->addSql('ALTER TABLE project_entry DROP FOREIGN KEY FK_387494EEE1FD4933');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE submission DROP FOREIGN KEY FK_DB055AF3A76ED395');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE submission');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('ALTER TABLE category_choice CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE miscellaneous_entry CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE operation_entry CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE project CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE project_entry DROP FOREIGN KEY FK_387494EE166D1F9C');
        $this->addSql('ALTER TABLE project_entry CHANGE id id INT NOT NULL');
    }
}
