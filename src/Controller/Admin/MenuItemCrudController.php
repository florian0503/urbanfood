<?php

namespace App\Controller\Admin;

use App\Entity\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

/**
 * @extends AbstractCrudController<MenuItem>
 */
class MenuItemCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return MenuItem::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Plat')
            ->setEntityLabelInPlural('Plats')
            ->setDefaultSort(['category' => 'ASC', 'position' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield AssociationField::new('category', 'Catégorie');
        yield TextField::new('name', 'Nom');
        yield TextField::new('description', 'Description');
        yield TextField::new('price', 'Prix (ex. 9,90)');
        yield IntegerField::new('position', 'Position dans la catégorie');
        yield BooleanField::new('featured', 'Mis en avant (accueil)');
        yield IntegerField::new('featuredPosition', 'Ordre en accueil')
            ->setRequired(false)
            ->hideOnIndex();
        yield TextField::new('tag', 'Tag pilule (ex. BEST-SELLER)')
            ->setRequired(false)
            ->hideOnIndex();
        yield TextField::new('featuredDescription', 'Description accueil')
            ->setRequired(false)
            ->hideOnIndex();
    }
}
