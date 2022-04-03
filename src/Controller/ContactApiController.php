<?php
namespace App\Controller;

use App\Repository\ContactRepository;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactApiController extends AbstractController
{
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
