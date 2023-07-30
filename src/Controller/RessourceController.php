<?php

namespace App\Controller;

use App\Entity\Ressource;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\RessourceType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\RessourceRepository;

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
     * @Route("/add/ressource", name="addressource")
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




    /**
     * @Route("/edit/{idsection}/ressource/{id}", name="editressource")
     */
    public function editressource(Ressource $ressource,Request $request)
    {   
        $ressource1 = new Ressource();
        $idsection = $ressource->getIdsection();
        $ressource1->setId($ressource->getId());
        $ressource1->setTitre($ressource->getTitre());
        $ressource1->setType($ressource->getType());
        $ressource1->setIdsection($ressource->getIdsection());
        $form = $this->createForm(RessourceType::class, $ressource1);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ressource1=$form->getData();
            $lien = $form['lien']->getData();
            $lien_name=$lien->getClientOriginalName();
            $lien->move($this->getParameter("photo_directory"),$lien_name);
            $ressource1->setLien($lien_name);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($ressource);
            $entityManager->flush();
            $entityManager->persist($ressource1);
            $entityManager->flush();

            return $this->redirectToRoute('showressource',['idsection' => $idsection]); 
        }
        return $this->render('ressource/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    

    /**
     * @Route("/delete/{idsection}/ressource/{id}", name="deleteressource")
     */
    public function deleteressource(Ressource $ressource,Request $request,RessourceRepository $ressourcerepository,$id,$idsection)
    {
            $ressource = $ressourcerepository->findOneBy(['id' => $id]);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($ressource);
            $entityManager->flush();
            return $this->redirectToRoute('showressource',['idsection' => $idsection]); 
    }






     /**
     * @Route("/show/{idsection}/ressource", name="showressource")
     */

     public function showressource(RessourceRepository $ressourceRepository,$idsection)
    {    $ressource = $ressourceRepository->findBy(['idsection' => $idsection]);
        
        return $this->render('ressource/showressource.html.twig', [
            'ressources' => $ressource
        ]);
    }

    /**
     * @Route("/download/{id}/doc", name="download_doc")
     */
    public function downloaddoc(RessourceRepository $ressourceRepository,$id)
    {   
        
        $ressource = $ressourceRepository->find($id);
        $ressourceTitre=$ressource->getTitre();
        $ressourceType=$ressource->getType();
        $ressourceLien=$ressource->getLien();
        $filePath = $this->getParameter("photo_directory")."/$ressourceLien";
        $response = new Response(file_get_contents($filePath));
        $response->headers->set('Content-Type',$ressourceType);
        $response->headers->set('Content-Disposition', "attachment; filename=$ressourceTitre");
        return $response;
        
    }
}


