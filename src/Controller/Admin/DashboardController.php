<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Categorie;
use App\Entity\Contact;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {

        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        //génerer une url afin d'accéder a la page d'accueil du crud article
        $url = $adminUrlGenerator
            ->setController(ArticleCrudController::class)
            ->generateUrl();
        //rediriger vers cette url
        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('salam');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToUrl('Accueil',"fa fa-home",$this->generateUrl("app_accueil"));
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-hippo');
        yield MenuItem::section("Articles",'fa fa-dragon');
        //creer un sous-menu pour article
        yield MenuItem::subMenu("Actions")
            ->setSubItems([
                MenuItem::linkToCrud("Lister Articles",'fa fa-plus',Article::class)->setAction(Crud::PAGE_INDEX)->setDefaultSort(['creatAt'=> 'DESC']),
                MenuItem::linkToCrud("Ajouter article",'fa fa-plus',Article::class)->setAction(Crud::PAGE_NEW)

            ]);
        yield MenuItem::section("Categorie",'fa fa-otter');
        yield MenuItem::subMenu("Actions")
            ->setSubItems([
                MenuItem::linkToCrud("Lister Categorie",'fa fa-plus',Categorie::class)->setAction(Crud::PAGE_INDEX),
                MenuItem::linkToCrud("Ajouter Categorie",'fa fa-plus',Categorie::class)->setAction(Crud::PAGE_NEW)

            ]);
        yield MenuItem::section("Contact");
        yield MenuItem::subMenu("Actions")->setSubItems([
           MenuItem::linkToCrud("lister Contact","fa fa-plus",Contact::class)->setAction(Crud::PAGE_INDEX)
        ]);



    }
}
