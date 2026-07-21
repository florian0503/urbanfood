<?php

namespace App\Controller\Admin;

use App\Entity\MenuCategory;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

/**
 * @extends AbstractCrudController<MenuCategory>
 */
class MenuCategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return MenuCategory::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Catégorie')
            ->setEntityLabelInPlural('Catégories')
            ->setDefaultSort(['position' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('name', 'Nom');
        yield TextField::new('tag', 'Tag affiché (ex. GRATINÉS MINUTE)');
        yield IntegerField::new('position', 'Position');
    }
}
