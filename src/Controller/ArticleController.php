<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Repository\CommentaireRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ArticleController extends AbstractController
{
    private ArticleRepository $articleRepository;
    private CommentaireRepository $commentaireRepository;
    private UtilisateurRepository $utilisateurRepository;

    //Demander a symfony d'injecter une instance de ArticleRepository
    //à la création du contrôleur (instance de ArticleController)
    public function __construct(ArticleRepository $articleRepository,CommentaireRepository $commentaireRepository,UtilisateurRepository $utilisateurRepository){
        $this->articleRepository = $articleRepository;
        $this->commentaireRepository = $commentaireRepository;
        $this->utilisateurRepository = $utilisateurRepository;
    }



    #[Route('/articles', name: 'app_articles')]
    // a l'appel de la méthode symfony va créer un objet
    // de la classe ArticleRepository et passer en paramétre de la méthode
    //Mécanisme : INJECTION DE DEPANDENCES

    public function getArticles(PaginatorInterface $paginator,Request $request): Response
    {
        //récupécurer les informations dans la DB
        // Le côntrolleur fait appel au modèle (classe du modèle)
        //afin de récupérer la liste des articles
        // $repository = new ArticleRepository();
        //mise en place paginator
        $articles = $paginator->paginate(
            $this->articleRepository->findBy([],['creatAt' => 'DESC']),
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('article/index.html.twig',[
            "articles" => $articles
        ])
            ;
    }

    #[Route('/articles/{slug}', name: 'app_article_slug')]
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
        $commentaires = $this->commentaireRepository->findBy(["id_article"=>$this->articleRepository->findBy(["slug"=>$slug])]);

        return $this->render('article/contenue.html.twig',[
            "article" => $article,
            "commentaires" => $commentaires
        ])
            ;
    }
    #[Route('/articles/nouveau', name: 'app_article_nouveau',priority: 1)]
    public function insert(SluggerInterface $slugger) :Response{
        $article= new Article();
        //création du formulaire
        $formArticle = $this->createForm(ArticleType::class,$article);
        //appel de la vue twig permettant d'afficher le formulaire
        return $this->renderForm('article/nouveau.html.twig',[
            'formArticle' => $formArticle
        ]);
        /*$article->setTitre('Nouvel article 2')
                ->setContenu('Contenu du nouvel article 2')
                ->setSlug($slugger->slug($article->getTitre())->lower())
                ->setCreatAt(new \DateTime());
        $this->articleRepository->add($article,true);

        return $this->redirectToRoute("app_articles");*/

    }

}
