<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211128172630 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE miscellaneous ADD CONSTRAINT FK_427B45FEA2DBE4F5 FOREIGN KEY (submission_id_id) REFERENCES submission (id)');
        $this->addSql('ALTER TABLE operation ADD CONSTRAINT FK_1981A66DA2DBE4F5 FOREIGN KEY (submission_id_id) REFERENCES submission (id)');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EEA2DBE4F5 FOREIGN KEY (submission_id_id) REFERENCES submission (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE submission ADD CONSTRAINT FK_DB055AF39D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE miscellaneous DROP FOREIGN KEY FK_427B45FEA2DBE4F5');
        $this->addSql('ALTER TABLE operation DROP FOREIGN KEY FK_1981A66DA2DBE4F5');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EEA2DBE4F5');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE submission DROP FOREIGN KEY FK_DB055AF39D86650F');
    }
}
