<?php

namespace App\Controller\Admin;

use App\Entity\Nationalite;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class NationaliteCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Nationalite::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('libelle'),
        ];
    }
    
}
