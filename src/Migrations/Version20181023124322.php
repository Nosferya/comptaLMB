<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181023124322 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE saisie_user');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE saisie_user (saisie_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_B0FE8D4CA12433ED (saisie_id), INDEX IDX_B0FE8D4CA76ED395 (user_id), PRIMARY KEY(saisie_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE saisie_user ADD CONSTRAINT FK_B0FE8D4CA12433ED FOREIGN KEY (saisie_id) REFERENCES saisie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE saisie_user ADD CONSTRAINT FK_B0FE8D4CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }
}
