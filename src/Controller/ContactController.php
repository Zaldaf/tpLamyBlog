<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use App\service\EmailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    private ContactRepository $contactRepository;

    public function __construct(ContactRepository $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }


    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request,EmailService $emailService): Response
    {
        $contact= new Contact();
        //création du formulaire
        $formContact = $this->createForm(ContactType::class,$contact);

        //Reconnaitre si le formulaire a été soumis ou pas
        $formContact->handleRequest($request);
        //Est-ce que le formulaire a été soumis
        if ($formContact->isSubmitted() && $formContact->isValid()){
            $contact->setCreateAt(new \DateTime());
            //insert l'article dans la base de données
            $this->contactRepository->add($contact,true);
            $emailService->envoyerEmail($contact->getEmail(),"admin@test.fr",$contact->getObjet(),"email/email.html.twig",[
                "prenom"=>$contact->getPrenom(),
                "nom"=>$contact->getNom(),
                "contenu"=>$contact->getContenu(),
                "objet"=>$contact->getObjet()
                ]);
            return $this->redirectToRoute("app_articles");
        }

        return $this->renderForm('contact/index.html.twig', [
            'formContact' => $formContact,
        ]);
    }
}
