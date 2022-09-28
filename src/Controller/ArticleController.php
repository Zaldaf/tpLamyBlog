<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    private ArticleRepository $articleRepository;

    //Demander a symfony d'injecter une instance de ArticleRepository
    //à la création du contrôleur (instance de ArticleController)
    public function __construct(ArticleRepository $articleRepository){
        $this->articleRepository = $articleRepository;
    }



    #[Route('/articles', name: 'app_articles')]
    // a l'appel de la méthode symfony va créer un objet
    // de la classe ArticleRepository et passer en paramétre de la méthode
    //Mécanisme : INJECTION DE DEPANDENCES

    public function getArticles(): Response
    {
        //récupécurer les informations dans la DB
        // Le côntrolleur fait appel au modèle (classe du modèle)
        //afin de récupérer la liste des articles
        // $repository = new ArticleRepository();
        $articles = $this->articleRepository->findBy([],['creatAt' => 'DESC']);

        return $this->render('article/index.html.twig',[
            "articles" => $articles
        ])
            ;
    }

    #[Route('/contenue/{slug}', name: 'app_contenue_slug')]
    // a l'appel de la méthode symfony va créer un objet
        // de la classe ArticleRepository et passer en paramétre de la méthode
        //Mécanisme : INJECTION DE DEPANDENCES

    public function getContenue($slug): Response
    {
        //récupécurer les informations dans la DB
        // Le côntrolleur fait appel au modèle (classe du modèle)
        //afin de récupérer la liste des articles
        // $repository = new ArticleRepository();
        $article = $this->articleRepository->findOneBy(["slug"=>$slug]);

        return $this->render('article/contenue.html.twig',[
            "article" => $article
        ])
            ;
    }
}
