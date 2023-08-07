<?php

namespace App\Controller;
use App\Entity\Formation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\SectionRepository;
use App\Repository\FormationRepository;
use App\Repository\RessourceRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Form\FormationFormType;




class FormationController extends AbstractController
{
    /**
     * @Route("/formation/add", name="addformation")
     */
    public function addformation(Request $request)
    {   $user=$this->getUser();
        if($user){
        $formation=new Formation;
        $form = $this->createForm(FormationFormType::class, $formation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $formation=$form->getData();
            //image upload
            $image = $form['image']->getData();
            $image_name=$image->getClientOriginalName();
            $image->move($this->getParameter("photo_directory"),$image_name);
            $formation->setImage($image_name);
            //..........
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($formation);
            $entityManager->flush();

            return $this->redirectToRoute('showformation'); 
        }

        return $this->render('formation/index.html.twig',[
            'form' => $form->createView()
        ]);
        }
        else{
            return $this->redirectToRoute('home'); 
        }
    }


       /**
     * @Route("/edit/formation/{id}", name="editformation")
     */
    public function editformation(Formation $formation,Request $request)
    {   $user=$this->getUser();
        if($user){
        $formation->setImage(null);
        $form = $this->createForm(FormationFormType::class, $formation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $formation=$form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($formation);
            $entityManager->flush();

            return $this->redirectToRoute('showformation'); 
        }
        return $this->render('formation/edit.html.twig', [
            'form' => $form->createView()
        ]);
        }
        else{
            return $this->redirectToRoute('home'); 
        }
     }

         /**
     * @Route("/delete/formation/{id}", name="deleteformation")
     */
    public function deleteformation(Formation $formation,Request $request,SectionRepository $sectionrepository,RessourceRepository $ressourcerepository)
    {       $user=$this->getUser();
            if($user){
            $idformation=$formation->getId();
            $sectionformation=$sectionrepository->findOneBy(['idformation'=>$idformation]);
            $entityManager = $this->getDoctrine()->getManager();
            while ($sectionformation) {
                $idsection=$sectionformation->getId();
                $ressourcesection=$ressourcerepository->findOneBy(['idsection'=>$idsection]);
                while($ressourcesection){
                    $entityManager->remove($ressourcesection);
                    $entityManager->flush();
                    $ressourcesection=$ressourcerepository->findOneBy(['idsection'=>$idsection]);

                }
                    $entityManager->remove($sectionformation);
                    $entityManager->flush();
                    $sectionformation=$sectionrepository->findOneBy(['idformation'=>$idformation]);

            }
            $entityManager->remove($formation);
            $entityManager->flush();
            return $this->redirectToRoute('showformation'); 
        }
        else{
            return $this->redirectToRoute('home'); 
        }
    }
    

     /**
     * @Route("/show/formation", name="showformation")
     */

     public function showformation(FormationRepository $formationrepository)
    {   $user=$this->getUser();
        if($user){ 
        $formation = $formationrepository->findAll();
        
        return $this->render('formation/showformation.html.twig', [
            'formation' => $formation
        ]);
        }
        else {
            return $this->redirectToRoute('home');
        }
    }

    /**
     * @Route("/search", name="search_form")
     */
    public function searchForm(Request $request)
    {   $user=$this->getUser();
        if($user){ 
        $query = $request->query->get('query');
        
        $formations = $this->getDoctrine()->getRepository(Formation::class)->findByTitre($query);

        return $this->render('formation/showformation.html.twig', [
            'formation' => $formations,
            'query' => $query,
        ]);
        }
        else {
            return $this->redirectToRoute('home');
        }

    }


}
