<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BienvenueController extends AbstractController
{
    #[Route('/welcome', name: 'app_bienvenue')]
    public function index(): Response
    {

        return $this->render('bienvenue/index.html.twig');
    }
    #[Route('/bienvenue/{nom}', name: 'app_bienvenue_personne')]
    public function BienvenuePersonne($nom): Response
    {

        return $this->render('bienvenue/bienvenue_personne.html.twig',[
            "nom"  => $nom
        ]);
    }
    #[Route('/bienvenus', name: 'app_bienvenus')]
    public function Bienvenus(): Response
    {
        //declarer un tableau avec 3 prénoms
        $noms = ["titouan","killian","eude"];

        //La vue affiche la bienvenus au 3 prénoms
        return $this->render('bienvenue/bienvenus.html.twig',[ "noms" => $noms]);
    }
}
