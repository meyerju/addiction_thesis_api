<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190410080444 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE person (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, birth_date DATETIME NOT NULL, gender VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE therapist (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE action_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE file (id INT AUTO_INCREMENT NOT NULL, patient_id INT NOT NULL, name VARCHAR(255) DEFAULT NULL, upload_date DATETIME NOT NULL, INDEX IDX_8C9F36106B899279 (patient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE file_detail (id INT AUTO_INCREMENT NOT NULL, file_id INT NOT NULL, action_type_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_9611592C93CB796C (file_id), INDEX IDX_9611592C1FEE0472 (action_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE patient_incident (id INT AUTO_INCREMENT NOT NULL, file_detail_id INT NOT NULL, date DATETIME NOT NULL, longitude DOUBLE PRECISION NOT NULL, latitude DOUBLE PRECISION NOT NULL, INDEX IDX_A86F9BF56DFBCCD (file_detail_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE patient (id INT AUTO_INCREMENT NOT NULL, person_id INT NOT NULL, addiction_name VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_1ADAD7EB217BBB47 (person_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE patient_therapist (patient_id INT NOT NULL, therapist_id INT NOT NULL, INDEX IDX_AAB6B1106B899279 (patient_id), INDEX IDX_AAB6B11043E8B094 (therapist_id), PRIMARY KEY(patient_id, therapist_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE file ADD CONSTRAINT FK_8C9F36106B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE file_detail ADD CONSTRAINT FK_9611592C93CB796C FOREIGN KEY (file_id) REFERENCES file (id)');
        $this->addSql('ALTER TABLE file_detail ADD CONSTRAINT FK_9611592C1FEE0472 FOREIGN KEY (action_type_id) REFERENCES action_type (id)');
        $this->addSql('ALTER TABLE patient_incident ADD CONSTRAINT FK_A86F9BF56DFBCCD FOREIGN KEY (file_detail_id) REFERENCES file_detail (id)');
        $this->addSql('ALTER TABLE patient ADD CONSTRAINT FK_1ADAD7EB217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE patient_therapist ADD CONSTRAINT FK_AAB6B1106B899279 FOREIGN KEY (patient_id) REFERENCES patient (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE patient_therapist ADD CONSTRAINT FK_AAB6B11043E8B094 FOREIGN KEY (therapist_id) REFERENCES therapist (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE patient DROP FOREIGN KEY FK_1ADAD7EB217BBB47');
        $this->addSql('ALTER TABLE patient_therapist DROP FOREIGN KEY FK_AAB6B11043E8B094');
        $this->addSql('ALTER TABLE file_detail DROP FOREIGN KEY FK_9611592C1FEE0472');
        $this->addSql('ALTER TABLE file_detail DROP FOREIGN KEY FK_9611592C93CB796C');
        $this->addSql('ALTER TABLE patient_incident DROP FOREIGN KEY FK_A86F9BF56DFBCCD');
        $this->addSql('ALTER TABLE file DROP FOREIGN KEY FK_8C9F36106B899279');
        $this->addSql('ALTER TABLE patient_therapist DROP FOREIGN KEY FK_AAB6B1106B899279');
        $this->addSql('DROP TABLE person');
        $this->addSql('DROP TABLE therapist');
        $this->addSql('DROP TABLE action_type');
        $this->addSql('DROP TABLE file');
        $this->addSql('DROP TABLE file_detail');
        $this->addSql('DROP TABLE patient_incident');
        $this->addSql('DROP TABLE patient');
        $this->addSql('DROP TABLE patient_therapist');
    }
}
