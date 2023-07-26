<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    //methods: ['GET'] permet de préciser que la route ne répond qu'aux requêtes GET.Ceci est facultatif car GET est la méthode par défaut.
    #[Route('/contacts', name: 'app_contact', methods: ['GET'])]
    public function index(ContactRepository $contactRepository,Request $request): Response
    {
        $page = $request->query->getInt('page', 1);

        //Vérifier si la requête est faite par htmx


        if($request->headers->get('HX-Request') === 'true'){
            sleep(4);
            return $this->render('contact/liste_htmx.html.twig', [
                'contacts' => $contactRepository->paginate($page, 8),
            ]);
        }


        if($page < 1){
            return $this->redirectToRoute('app_contact', ['page' => 1]);
        }

        //$contacts= $contactRepository->findAll();

        $data = $contactRepository->paginate($page, 8);
        
        
        return $this->render('contact/liste.html.twig', [
            'contacts' => $data,
        ]);
    }


    #[Route('/contact/{id}', name: 'app_contact_show', methods: ['GET'])]
    public function show(ContactRepository $contactRepository, int $id): Response
    {
        $contact = $contactRepository->find($id);

        if(!$contact){
            //Envoyer une réponse 404
            throw $this->createNotFoundException("Le contact demandé n'existe pas");
        }

        return $this->render('contact/show.html.twig', [
            'contact' => $contact
        ]);
    }
}
