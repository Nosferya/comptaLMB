<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181016144221 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE setting (id INT AUTO_INCREMENT NOT NULL, revente_unitaire INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE setting_user (setting_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_4C27FAAFEE35BD72 (setting_id), INDEX IDX_4C27FAAFA76ED395 (user_id), PRIMARY KEY(setting_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE setting_saisie (setting_id INT NOT NULL, saisie_id INT NOT NULL, INDEX IDX_3BA452B5EE35BD72 (setting_id), INDEX IDX_3BA452B5A12433ED (saisie_id), PRIMARY KEY(setting_id, saisie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE setting_user ADD CONSTRAINT FK_4C27FAAFEE35BD72 FOREIGN KEY (setting_id) REFERENCES setting (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE setting_user ADD CONSTRAINT FK_4C27FAAFA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE setting_saisie ADD CONSTRAINT FK_3BA452B5EE35BD72 FOREIGN KEY (setting_id) REFERENCES setting (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE setting_saisie ADD CONSTRAINT FK_3BA452B5A12433ED FOREIGN KEY (saisie_id) REFERENCES saisie (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE setting_user DROP FOREIGN KEY FK_4C27FAAFEE35BD72');
        $this->addSql('ALTER TABLE setting_saisie DROP FOREIGN KEY FK_3BA452B5EE35BD72');
        $this->addSql('DROP TABLE setting');
        $this->addSql('DROP TABLE setting_user');
        $this->addSql('DROP TABLE setting_saisie');
    }
}
