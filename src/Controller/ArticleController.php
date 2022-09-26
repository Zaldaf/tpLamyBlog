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

    public function getArticles(ArticleRepository $repository): Response
    {
        //récupécurer les informations dans la DB
        // Le côntrolleur fait appel au modèle (classe du modèle)
        //afin de récupérer la liste des articles
        // $repository = new ArticleRepository();
        $articles = $repository->findBy([],['creatAt' => 'DESC'],5);

        return $this->render('article/index.html.twig',[
            "articles" => $articles
        ])
            ;
    }

    #[Route('/contenue/{id}', name: 'app_contenue_id')]
    // a l'appel de la méthode symfony va créer un objet
        // de la classe ArticleRepository et passer en paramétre de la méthode
        //Mécanisme : INJECTION DE DEPANDENCES

    public function getContenue(ArticleRepository $repository,$id): Response
    {
        //récupécurer les informations dans la DB
        // Le côntrolleur fait appel au modèle (classe du modèle)
        //afin de récupérer la liste des articles
        // $repository = new ArticleRepository();
        $article = $repository->find($id);

        return $this->render('article/contenue.html.twig',[
            "article" => $article
        ])
            ;
    }
}
