<?php

namespace App\Controller;

use App\Entity\Contact;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountController extends AbstractController
{
    /**
     * @Route("/login", name="account_login")
     */
    public function login()
    {
        return $this->render('account/login.html.twig');
    }

    /**
     * @Route("/backoffice", name="back_office")
     */
    public function backoffice(ManagerRegistry $doctrine)
    {
        $contact = $doctrine->getRepository(Contact::class)->findBy([],['id' => 'desc']);

        return $this->render('account/backoffice.html.twig',
    [
        'contacts' => $contact
    ]);
    }
}
