<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190417075931 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE patient_therapist');
        $this->addSql('ALTER TABLE patient ADD therapist_id INT NOT NULL');
        $this->addSql('ALTER TABLE patient ADD CONSTRAINT FK_1ADAD7EB43E8B094 FOREIGN KEY (therapist_id) REFERENCES therapist (id)');
        $this->addSql('CREATE INDEX IDX_1ADAD7EB43E8B094 ON patient (therapist_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE patient_therapist (patient_id INT NOT NULL, therapist_id INT NOT NULL, INDEX IDX_AAB6B11043E8B094 (therapist_id), INDEX IDX_AAB6B1106B899279 (patient_id), PRIMARY KEY(patient_id, therapist_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE patient_therapist ADD CONSTRAINT FK_AAB6B11043E8B094 FOREIGN KEY (therapist_id) REFERENCES therapist (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE patient_therapist ADD CONSTRAINT FK_AAB6B1106B899279 FOREIGN KEY (patient_id) REFERENCES patient (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE patient DROP FOREIGN KEY FK_1ADAD7EB43E8B094');
        $this->addSql('DROP INDEX IDX_1ADAD7EB43E8B094 ON patient');
        $this->addSql('ALTER TABLE patient DROP therapist_id');
    }
}
