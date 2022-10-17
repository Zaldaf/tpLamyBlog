<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\String\Slugger\SluggerInterface;
use function Sodium\add;

class ArticleCrudController extends AbstractCrudController
{
    private SluggerInterface $slugger;

 public function __construct(SluggerInterface $slugger)
{
    $this->slugger = $slugger;
}

    public static function getEntityFqcn(): string
    {
        return Article::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm()->setLabel('Num Article'),
            TextField::new('titre')->setLabel('Titre Article'),
            TextEditorField::new('contenu')->setSortable(false)->hideOnIndex(),
            AssociationField::new('categorie'),
            TextField::new('slug')->hideOnForm(),
            DateTimeField::new('creatAt')->hideOnForm(),
            BooleanField::new('isPublie')
        ];
    }
    //Redefinir persist entity qui vas être appelée lors de la création de l'article en bdd
    // générer l'ordre insert
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
       //verifier que la $entityInstance est une instance de la classe article
        if (!$entityInstance instanceof Article) return;
        $entityInstance->setCreatAt(new \DateTime())
                        ->setSlug($this->slugger->slug($entityInstance->getTitre())->lower());


        //appel a la méthode héritée afin de persister l'entité
        parent::persistEntity($entityManager,$entityInstance);
    }

    public function configureCrud(Crud $crud): Crud
    {
        $crud->setPageTitle(Crud::PAGE_INDEX,"Liste des articles");
        $crud->setPageTitle(Crud::PAGE_NEW,"Création Article");
        $crud->setPageTitle(Crud::PAGE_EDIT,"Modifier Article");
        $crud->setPaginatorPageSize(10);
        $crud->setDefaultSort(['creatAt'=> 'DESC']);
        return $crud;
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions->update(Crud::PAGE_INDEX, Action::NEW,function (Action $action){return
            $action->setLabel("Ajouter Article")->setIcon("fa fa-plus");
        });
        $actions->update(Crud::PAGE_NEW,Action::SAVE_AND_RETURN,function (Action $action){return $action->setLabel('Ajouter Article')->setIcon("fa fa-plus");});
        $actions->remove(Crud::PAGE_NEW,Action::SAVE_AND_ADD_ANOTHER);
        $actions->add(Crud::PAGE_INDEX,Action::DETAIL);

        return $actions;

    }

    public function configureFilters(Filters $filters): Filters
    {

        $filters->add("titre")
                ->add("creatAt");


        return $filters;
    }


}
