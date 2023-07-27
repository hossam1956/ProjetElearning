<?php

namespace App\Controller;

use App\Entity\Ressource;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\RessourceType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class RessourceController extends AbstractController
{

      /**
     * @Route("/home", name="home")
     */
    public function home()

    {   
        return $this->render('home.html.twig');

    }

    /**
     * @Route("/add/ressource", name="add_ressource")
     */
    public function addressource(Request $request)

    {   
        $ressource = new Ressource();
        $form = $this->createForm(RessourceType::class, $ressource);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ressource=$form->getData();
            //image upload
            $lien = $form['lien']->getData();
            $lien_name=$lien->getClientOriginalName();
            $lien->move($this->getParameter("photo_directory"),$lien_name);
            $ressource->setLien($lien_name);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ressource);
            $entityManager->flush();

            // You can add a flash message here to notify the user about successful registration.
           // $this->addFlash('success', 'Registration successful!');

            return $this->redirectToRoute('home'); 
        }
        return $this->render('ressource/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
