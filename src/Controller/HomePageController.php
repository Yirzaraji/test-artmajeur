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
            $contact_name = $form["name"]->getData();
            $entityManager->persist($contact_message);
            $entityManager->flush();

            //Return json file with name of the user
            $response = $this->json($contact_message, 200, []);
            $fs = new \Symfony\Component\Filesystem\Filesystem();
            try {
                $fs->dumpFile('..\src\Entity\Json\contact_'.$contact_name.'.json', $response);     
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
