<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ContactCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Contact::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm()->setLabel('Id contact')->hideOnDetail()->hideOnIndex(),
            TextField::new('nom')->setLabel('nom'),
            TextField::new('prenom')->setLabel('prenom'),
            TextField::new('objet')->setLabel('objet du mail'),
            TextEditorField::new('contenu')->setSortable(false)->hideOnIndex(),
            DateTimeField::new('createAT'),
            EmailField::new('email')
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof Contact) return;
        $entityInstance->setCreateAt(new \DateTime());
        parent::persistEntity($entityManager, $entityInstance);
    }
    public function configureCrud(Crud $crud): Crud
    {
        $crud->setPageTitle(Crud::PAGE_INDEX,"Liste des articles");
        $crud->setPaginatorPageSize(10);
        $crud->setDefaultSort(['createAt'=> 'DESC']);
        return $crud;
    }
    public function configureActions(Actions $actions): Actions
    {

        $actions->add(Crud::PAGE_INDEX,Action::DETAIL);

        return $actions;

    }

}
