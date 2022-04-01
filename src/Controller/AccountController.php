<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
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
        //dump($groupUserMessages);
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
        
        $ReadContact = $manager->getRepository(Contact::class)->find($contact);

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
            'contact' => $contact,
            'ReadContact' => $ReadContact
        ]);
    }

    /**
     * @Route("/api/contact", name="api_contact", methods={"GET"})
     */
    public function readUserMessage(ContactRepository $contactRepository)
    {
        $contact = $contactRepository->findAll();
        $response = $this->json($contact, 200, []);
        return $response;

        $fs = new \Symfony\Component\Filesystem\Filesystem();
        try {
            $fs->dumpFile('..\src\Entity\Json\Contact.json', $response);     
        }
        catch (EntityNotFoundException $e) {
            echo 'Exception reçue : ',  $e->getMessage(), "\n";
        }    
    }
}
