<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211202135533 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE category_choice_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE miscellaneous_entry_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE operation_entry_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE project_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE project_entry_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE reset_password_request_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE submission_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE category_choice (id INT NOT NULL, category VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE miscellaneous_entry (id INT NOT NULL, submission_id INT NOT NULL, task VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, target_hours DOUBLE PRECISION NOT NULL, comment TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D1B2A08FE1FD4933 ON miscellaneous_entry (submission_id)');
        $this->addSql('CREATE TABLE operation_entry (id INT NOT NULL, submission_id INT NOT NULL, category VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, hours DOUBLE PRECISION NOT NULL, priority VARCHAR(255) NOT NULL, work_results TEXT DEFAULT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_AC064EFFE1FD4933 ON operation_entry (submission_id)');
        $this->addSql('CREATE TABLE project (id INT NOT NULL, name VARCHAR(255) NOT NULL, hours_sold INT NOT NULL, created DATE NOT NULL, status VARCHAR(255) NOT NULL, archive BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE project_entry (id INT NOT NULL, submission_id INT NOT NULL, project_id INT NOT NULL, description VARCHAR(255) NOT NULL, target_hours DOUBLE PRECISION NOT NULL, actual_hours DOUBLE PRECISION DEFAULT NULL, priority VARCHAR(255) NOT NULL, work_results TEXT DEFAULT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_387494EEE1FD4933 ON project_entry (submission_id)');
        $this->addSql('CREATE INDEX IDX_387494EE166D1F9C ON project_entry (project_id)');
        $this->addSql('CREATE TABLE reset_password_request (id INT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, expires_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7CE748AA76ED395 ON reset_password_request (user_id)');
        $this->addSql('COMMENT ON COLUMN reset_password_request.requested_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN reset_password_request.expires_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE submission (id INT NOT NULL, user_id INT NOT NULL, created DATE NOT NULL, updated DATE NOT NULL, submission_month DATE NOT NULL, form_type VARCHAR(255) NOT NULL, workdays DOUBLE PRECISION NOT NULL, planned_absences DOUBLE PRECISION NOT NULL, further_absences DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DB055AF3A76ED395 ON submission (user_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(20) NOT NULL, surname VARCHAR(20) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('ALTER TABLE miscellaneous_entry ADD CONSTRAINT FK_D1B2A08FE1FD4933 FOREIGN KEY (submission_id) REFERENCES submission (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE operation_entry ADD CONSTRAINT FK_AC064EFFE1FD4933 FOREIGN KEY (submission_id) REFERENCES submission (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_entry ADD CONSTRAINT FK_387494EEE1FD4933 FOREIGN KEY (submission_id) REFERENCES submission (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_entry ADD CONSTRAINT FK_387494EE166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE submission ADD CONSTRAINT FK_DB055AF3A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE project_entry DROP CONSTRAINT FK_387494EE166D1F9C');
        $this->addSql('ALTER TABLE miscellaneous_entry DROP CONSTRAINT FK_D1B2A08FE1FD4933');
        $this->addSql('ALTER TABLE operation_entry DROP CONSTRAINT FK_AC064EFFE1FD4933');
        $this->addSql('ALTER TABLE project_entry DROP CONSTRAINT FK_387494EEE1FD4933');
        $this->addSql('ALTER TABLE reset_password_request DROP CONSTRAINT FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE submission DROP CONSTRAINT FK_DB055AF3A76ED395');
        $this->addSql('DROP SEQUENCE category_choice_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE miscellaneous_entry_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE operation_entry_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE project_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE project_entry_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE reset_password_request_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE submission_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE user_id_seq CASCADE');
        $this->addSql('DROP TABLE category_choice');
        $this->addSql('DROP TABLE miscellaneous_entry');
        $this->addSql('DROP TABLE operation_entry');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE project_entry');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE submission');
        $this->addSql('DROP TABLE "user"');
    }
}
