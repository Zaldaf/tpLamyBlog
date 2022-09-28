<?php

namespace App\Controller;
use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{
    #[Route('/categorie', name: 'app_categorie')]
    public function index(CategorieRepository $categorieRepository): Response
    {

        $categories= $categorieRepository->findBy([],['titre' => 'ASC']);

        return $this->render('categorie/index.html.twig',[
            "categories" => $categories
        ])
            ;
    }

    #[Route('/categorie/{slug}', name: 'app_categorie_slug')]
    // a l'appel de la méthode symfony va créer un objet
        // de la classe ArticleRepository et passer en paramétre de la méthode
        //Mécanisme : INJECTION DE DEPANDENCES

    public function getArticles(ArticleRepository $articleRepository ,CategorieRepository $categorieRepository, $slug): Response
    {
        //récupécurer les informations dans la DB
        // Le côntrolleur fait appel au modèle (classe du modèle)
        //afin de récupérer la liste des articles
        // $repository = new ArticleRepository();
        $categories = $articleRepository->findBy(["categorie"=>$categorieRepository->findOneBy(["slug"=>$slug])]);


        return $this->render('categorie/articlesCategorie.html.twig',[
            "categories" => $categories
        ])
            ;
    }




}
