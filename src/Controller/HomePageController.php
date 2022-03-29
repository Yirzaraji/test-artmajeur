<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Entity\Contact;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomePageController extends AbstractController
{
    /**
     * @Route("/", name="app_home_page")
     */
    public function home(Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $contact_message = new Contact();
        $form = $this->createForm(ContactType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $contact_message = $form->getData();
            $entityManager->persist($contact_message);
            $entityManager->flush();
            
            /* $this->addFlash(
                'success',
                'Votre message a bien été <b<envoyé</b>'
            ); */
            return $this->redirectToRoute('app_home_page');
        }
        
        return $this->render('index.html.twig', [
            'contactForm' => $form->createView(),
            'controller_name' => 'HomePageController',
        ]);
    }
}
