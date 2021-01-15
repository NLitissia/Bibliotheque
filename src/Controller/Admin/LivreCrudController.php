<?php

namespace App\Controller\Admin;

use App\Entity\Livre;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;


class LivreCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Livre::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            //TextField::new('nom')
            TextField::new("isbn"),
            TextField::new("titre"),
            MoneyField::new("prix")->setCurrency('EUR'),
            AssociationField::new('editeur')->autocomplete(),
            IntegerField::new("annee"),
            TextField::new("langue"),
            AssociationField::new('auteur')->autocomplete(),
            AssociationField::new('genre')->autocomplete(),

        ];
    }
    
}
