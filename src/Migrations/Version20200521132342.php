<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200521132342 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE address ADD creator VARCHAR(255) NOT NULL, CHANGE second_line second_line VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE company_id company_id INT DEFAULT NULL, CHANGE roles roles JSON NOT NULL, CHANGE password_requested_at password_requested_at DATETIME DEFAULT NULL, CHANGE token token VARCHAR(255) DEFAULT NULL, CHANGE user_registrated_at user_registrated_at DATETIME DEFAULT NULL, CHANGE creator creator INT DEFAULT NULL, CHANGE city city VARCHAR(255) DEFAULT NULL, CHANGE address address VARCHAR(255) DEFAULT NULL, CHANGE postal_code postal_code VARCHAR(255) DEFAULT NULL, CHANGE telephone telephone VARCHAR(35) DEFAULT NULL');
        $this->addSql('ALTER TABLE document CHANGE name name VARCHAR(255) DEFAULT NULL, CHANGE kind kind VARCHAR(255) DEFAULT NULL, CHANGE filename filename VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE profile CHANGE image_name image_name VARCHAR(255) DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL, CHANGE city city VARCHAR(255) DEFAULT NULL, CHANGE address address VARCHAR(255) DEFAULT NULL, CHANGE postal_code postal_code VARCHAR(255) DEFAULT NULL, CHANGE telephone telephone VARCHAR(35) DEFAULT NULL');
        $this->addSql('ALTER TABLE property CHANGE price price INT DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL, CHANGE landing landing VARCHAR(255) DEFAULT NULL, CHANGE gender gender VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE lease CHANGE signature_date signature_date DATETIME DEFAULT NULL, CHANGE effect_date effect_date DATETIME DEFAULT NULL, CHANGE length length VARCHAR(255) DEFAULT NULL, CHANGE rent rent DOUBLE PRECISION DEFAULT NULL, CHANGE vat vat DOUBLE PRECISION DEFAULT NULL, CHANGE payment_term payment_term INT DEFAULT NULL, CHANGE charges charges DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE company CHANGE web_site web_site VARCHAR(255) DEFAULT NULL, CHANGE telephone telephone VARCHAR(255) DEFAULT NULL, CHANGE creator creator INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE address DROP creator, CHANGE second_line second_line VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE company CHANGE web_site web_site VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE telephone telephone VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE creator creator INT DEFAULT NULL');
        $this->addSql('ALTER TABLE document CHANGE name name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE kind kind VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE filename filename VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE lease CHANGE signature_date signature_date DATETIME DEFAULT \'NULL\', CHANGE effect_date effect_date DATETIME DEFAULT \'NULL\', CHANGE length length VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE rent rent DOUBLE PRECISION DEFAULT \'NULL\', CHANGE charges charges DOUBLE PRECISION DEFAULT \'NULL\', CHANGE vat vat DOUBLE PRECISION DEFAULT \'NULL\', CHANGE payment_term payment_term INT DEFAULT NULL');
        $this->addSql('ALTER TABLE profile CHANGE image_name image_name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\', CHANGE city city VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE address address VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE postal_code postal_code VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE telephone telephone VARCHAR(35) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE property CHANGE price price INT DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\', CHANGE landing landing VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE gender gender VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE company_id company_id INT DEFAULT NULL, CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE password_requested_at password_requested_at DATETIME DEFAULT \'NULL\', CHANGE token token VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE user_registrated_at user_registrated_at DATETIME DEFAULT \'NULL\', CHANGE creator creator INT DEFAULT NULL, CHANGE city city VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE address address VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE postal_code postal_code VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE telephone telephone VARCHAR(35) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
