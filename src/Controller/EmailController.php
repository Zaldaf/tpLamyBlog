<?php

namespace App\Controller;


use App\service\EmailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;

class EmailController extends AbstractController
{
    #[Route('/email', name: 'app_email')]
    public function index(EmailService $emailService): Response
    {
       $emailService->envoyerEmail("emetteur@test.fr","destinataire@test.fr","Mail reçus","email/email.html.twig",["prenom"=> "Ludo","nom"=>"lafeve"]);
        return $this->redirectToRoute("app_articles");
    }
}
