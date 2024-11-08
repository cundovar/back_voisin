<?php

// src/Service/DatabaseTablePrefix.php

namespace App\Service;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;

class DatabaseTablePrefix implements EventSubscriber
{
    private $prefix;

    // Lors de l'appel de ce service, on lui passera notre préfixe.
    public function __construct($prefix)
    {
        $this->prefix = $prefix;
    }

    // Méthode qui indique à Doctrine quel événement écouter
    public function getSubscribedEvents()
    {
        return ['loadClassMetadata'];
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $classMetadata = $eventArgs->getClassMetadata();

        // Ajouter le préfixe au nom de la table principale
        if (!$classMetadata->isInheritanceTypeSingleTable() || $classMetadata->getName() === $classMetadata->rootEntityName) {
            $classMetadata->setPrimaryTable([
                'name' => $this->prefix . $classMetadata->getTableName()
            ]);
        }

        // Ajouter le préfixe aux tables de jointure MANY_TO_MANY
        foreach ($classMetadata->getAssociationMappings() as $fieldName => $mapping) {
            if ($mapping['type'] === ClassMetadata::MANY_TO_MANY && $mapping['isOwningSide']) {
                $mappedTableName = $mapping['joinTable']['name'];
                $classMetadata->associationMappings[$fieldName]['joinTable']['name'] = $this->prefix . $mappedTableName;
            }
        }
    }
}
