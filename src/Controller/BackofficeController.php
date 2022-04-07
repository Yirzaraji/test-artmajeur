<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BackofficeController extends AbstractController
{
    /**
     * @Route("/backoffice", name="back_office")
     */
    public function backoffice(Request $request, PaginatorInterface $paginator, ManagerRegistry $doctrine) :response
    {
        /* $contact = $doctrine->getRepository(Contact::class)->findBy([
            'id' => 'desc'
        ]); */

        $groupUserMail = $doctrine->getRepository(Contact::class)->groupUserMail();
        //pagination
        $pagination = $paginator->paginate(
            $groupUserMail,
            $request->query->getInt('page', 1),
            5
        );
        //$groupUserMessages = $doctrine->getRepository(Contact::class)->groupUserMessage();
        
        return $this->render('backoffice.html.twig',
    [
        'groupUserMail' => $pagination
    ]);
    }

    /**
     * @Route("/backoffice/edit-{id}-{mail}", name="contact")
     * @return Response
     */
    public function edit(Contact $contact, Request $request, EntityManagerInterface $manager)
    {
        
        //$ReadContact = $manager->getRepository(Contact::class)->find($contact);
        $ContactMailsGroup = $manager->getRepository(Contact::class)->findBy([
            'mail' => $contact->getMail()
        ]);

        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        //si le form est soumis && si il est valide
        if ($form->isSubmitted() && $form->isValid()) {       
            $manager->persist($contact);
            $manager->flush();
            
            return $this->redirectToRoute('back_office');      
        }

        return $this->render('backoffice-edit.html.twig', [
            'form' => $form->createView(),
            'contact' => $contact,
            'ContactMailsGroup' => $ContactMailsGroup
        ]);
    }
}
