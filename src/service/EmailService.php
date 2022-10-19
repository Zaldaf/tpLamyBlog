<?php

namespace App\service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class EmailService
{
    private MailerInterface $mailer;

    /**
     * @param MailerInterface $mailer
     */
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param string $emetteur
     * @param string $destinataire
     * @param string $objet
     * @param string $template
     * @param array $contexte
     * @return void
     */
    public function envoyerEmail(string $emetteur,string $destinataire,string $objet,string $template, array $contexte):void{
        $email = new TemplatedEmail();
        $email->from($emetteur)
            ->to($destinataire)
            ->subject($objet)
            ->htmlTemplate($template)
            ->context($contexte);
        //envoyer le mail
        $this->mailer->send($email);

    }
}