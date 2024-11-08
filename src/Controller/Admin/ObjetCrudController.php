<?php

namespace App\Controller\Admin;

use App\Entity\Objet;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ObjetCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Objet::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
           
            TextField::new('title'),
            TextEditorField::new('description'),
            AssociationField::new('category')->setLabel('Catégorie'),
            // Autres champs de l'entité Objet
        ];
    }
    
}
