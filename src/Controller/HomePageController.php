<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityNotFoundException;
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

            //Return json file with name + ID + message
            $response_json = [
                "name" => $contact_message->getName(), 
                "mail"=> $contact_message->getMail(), 
                "message"=> $contact_message->getMessage()
            ];

            $response = $this->json($response_json, 200, []);
            $fs = new \Symfony\Component\Filesystem\Filesystem();
            try {
                $fs->dumpFile('..\src\Entity\Json\contact_'.$contact_message->getName().$contact_message->getId().'.json', $response);     
            }
            catch (EntityNotFoundException $e) {
                echo 'Exception reçue : ',  $e->getMessage(), "\n";
            }    

            return $this->redirectToRoute('app_home_page');
        }
        
        return $this->render('contact.html.twig', [
            'contactForm' => $form->createView()
        ]);
    }
}
