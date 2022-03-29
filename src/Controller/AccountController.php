<?php

namespace App\Controller;

use App\Entity\Contact;
use Doctrine\Persistence\ObjectManager;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
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
        
        $groupUserMessages = $doctrine->getRepository(Contact::class)->groupUserMessage();
        dump($groupUserMessages);
        return $this->render('account/backoffice.html.twig',
    [
        'contacts' => $contact,
        'groupUserMessages' => $groupUserMessages
    ]);
    }

    /**
     * user message state
     * 
     * @Route("/backoffice/edit-{id}", name="contact")
     * @return Response
     */
    public function edit(Contact $contact, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        //si le form est soumis && si il est valide
        if ($form->isSubmitted() && $form->isValid()) {       
                $manager->persist($contact);
                $manager->flush();
                
                return $this->redirectToRoute('back_office');      
        }

        return $this->render('account/contactedit.html.twig', [
            'editContactForm' => $form->createView(),
            'contact' => $contact
        ]);
    }
}
