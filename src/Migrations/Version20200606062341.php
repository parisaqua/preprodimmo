<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200606062341 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE profile ADD company_id INT DEFAULT NULL, ADD company_related TINYINT(1) NOT NULL, CHANGE user_id user_id INT DEFAULT NULL, CHANGE creator creator INT DEFAULT NULL, CHANGE image_name image_name VARCHAR(255) DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL, CHANGE telephoneM telephoneM VARCHAR(35) DEFAULT NULL, CHANGE telephone_h telephone_h VARCHAR(35) DEFAULT NULL, CHANGE telephone_o telephone_o VARCHAR(35) DEFAULT NULL');
        $this->addSql('ALTER TABLE profile ADD CONSTRAINT FK_8157AA0F979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('CREATE INDEX IDX_8157AA0F979B1AD6 ON profile (company_id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649979B1AD6');
        $this->addSql('DROP INDEX IDX_8D93D649979B1AD6 ON user');
        $this->addSql('ALTER TABLE user DROP company_id, DROP company_related, CHANGE roles roles JSON NOT NULL, CHANGE password_requested_at password_requested_at DATETIME DEFAULT NULL, CHANGE token token VARCHAR(255) DEFAULT NULL, CHANGE user_registrated_at user_registrated_at DATETIME DEFAULT NULL, CHANGE creator creator INT DEFAULT NULL');
        $this->addSql('ALTER TABLE document CHANGE name name VARCHAR(255) DEFAULT NULL, CHANGE kind kind VARCHAR(255) DEFAULT NULL, CHANGE filename filename VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE property CHANGE price price INT DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL, CHANGE landing landing VARCHAR(255) DEFAULT NULL, CHANGE gender gender VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE lease CHANGE signature_date signature_date DATETIME DEFAULT NULL, CHANGE effect_date effect_date DATETIME DEFAULT NULL, CHANGE length length VARCHAR(255) DEFAULT NULL, CHANGE rent rent DOUBLE PRECISION DEFAULT NULL, CHANGE charges charges DOUBLE PRECISION DEFAULT NULL, CHANGE vat vat DOUBLE PRECISION DEFAULT NULL, CHANGE payment_term payment_term INT DEFAULT NULL');
        $this->addSql('ALTER TABLE company CHANGE web_site web_site VARCHAR(255) DEFAULT NULL, CHANGE telephone telephone VARCHAR(255) DEFAULT NULL, CHANGE creator creator INT DEFAULT NULL');
        $this->addSql('ALTER TABLE location CHANGE second_line second_line VARCHAR(255) DEFAULT NULL, CHANGE lat lat DOUBLE PRECISION DEFAULT NULL, CHANGE lng lng DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE company CHANGE web_site web_site VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE telephone telephone VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE creator creator INT DEFAULT NULL');
        $this->addSql('ALTER TABLE document CHANGE name name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE kind kind VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE filename filename VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE lease CHANGE signature_date signature_date DATETIME DEFAULT \'NULL\', CHANGE effect_date effect_date DATETIME DEFAULT \'NULL\', CHANGE length length VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE rent rent DOUBLE PRECISION DEFAULT \'NULL\', CHANGE charges charges DOUBLE PRECISION DEFAULT \'NULL\', CHANGE vat vat DOUBLE PRECISION DEFAULT \'NULL\', CHANGE payment_term payment_term INT DEFAULT NULL');
        $this->addSql('ALTER TABLE location CHANGE second_line second_line VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE lat lat DOUBLE PRECISION DEFAULT \'NULL\', CHANGE lng lng DOUBLE PRECISION DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE profile DROP FOREIGN KEY FK_8157AA0F979B1AD6');
        $this->addSql('DROP INDEX IDX_8157AA0F979B1AD6 ON profile');
        $this->addSql('ALTER TABLE profile DROP company_id, DROP company_related, CHANGE user_id user_id INT DEFAULT NULL, CHANGE creator creator INT DEFAULT NULL, CHANGE image_name image_name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\', CHANGE telephoneM telephoneM VARCHAR(35) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE telephone_h telephone_h VARCHAR(35) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE telephone_o telephone_o VARCHAR(35) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE property CHANGE price price INT DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\', CHANGE landing landing VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE gender gender VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user ADD company_id INT DEFAULT NULL, ADD company_related TINYINT(1) NOT NULL, CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE password_requested_at password_requested_at DATETIME DEFAULT \'NULL\', CHANGE token token VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE user_registrated_at user_registrated_at DATETIME DEFAULT \'NULL\', CHANGE creator creator INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649979B1AD6 ON user (company_id)');
    }
}
