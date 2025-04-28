<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250421085549 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }
    public function up(Schema $schema): void
    {
        $table = $schema->getTable('demande_validation');
        
        // Ajouter les colonnes description et descriptionfr
        $table->addColumn('description', 'string', ['length' => 255, 'nullable' => true]);
        $table->addColumn('descriptionfr', 'string', ['length' => 255, 'nullable' => true]);
    }
    
    public function down(Schema $schema): void
    {
        $table = $schema->getTable('demande_validation');
        
        // Supprimer les colonnes description et descriptionfr
        $table->dropColumn('description');
        $table->dropColumn('descriptionfr');
    }
    
}
