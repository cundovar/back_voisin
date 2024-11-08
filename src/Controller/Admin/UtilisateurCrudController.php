<?php

namespace App\Controller\Admin;

use App\Entity\Utilisateur;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UtilisateurCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Utilisateur::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
       
    $fields = [
          
                IdField::new('id'),
                TextField::new('username'),
                TextField::new('email'),
                ArrayField::new('roles')->setLabel('Roles'), // Si vous avez un champ "roles" dans Utilisateur
               
        
    ];
     // Ne pas inclure le champ 'objets' lors de l'ajout d'un utilisateur
     if ($pageName !== Crud::PAGE_NEW) {
        $fields[] = AssociationField::new('objets')->setLabel('objets');
    
    }
    return $fields;
}}

