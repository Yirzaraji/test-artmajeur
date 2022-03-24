<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomePageController extends AbstractController
{
    /**
     * @Route("/contact", name="app_home_page")
     */
    public function home(Request $request): Response
    {

        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash(
                'success',
                'Votre message a bien été <b<envoyé</b>'
            );
            return $this->redirectToRoute('app_home_page');
        }
        
        return $this->render('index.html.twig', [
            'contactForm' => $form->createView(),
            'controller_name' => 'HomePageController',
        ]);
    }
}
