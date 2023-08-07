<?php

namespace App\Controller;
use App\Entity\Section;
use App\Entity\Formation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\SectionType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\SectionRepository;
use App\Repository\RessourceRepository;
use App\Repository\FormationRepository;
class SectionController extends AbstractController
{
    /**
     * @Route("/add/section", name="addsection")
     */
    public function addsection(Request $request)
    {   $user=$this->getUser();
        if($user){
        $section = new Section();
        $form = $this->createForm(SectionType::class, $section);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $section=$form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($section);
            $entityManager->flush();

            return $this->redirectToRoute('showformation'); 
        }
        return $this->render('section/index.html.twig', [
            'form' => $form->createView()
        ]);
        }
        else{
            return $this->redirectToRoute('home');
        }
    }

    /**
     * @Route("/edit/section/{id}", name="editsection")
     */
    public function editsection(Section $section,Request $request)
    {
        $user=$this->getUser();
        if($user){
        $form = $this->createForm(SectionType::class, $section);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $section=$form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($section);
            $entityManager->flush();

            return $this->redirectToRoute('showformation'); 
        }
        return $this->render('section/edit.html.twig', [
            'form' => $form->createView()
        ]);
        }
        else{
            return $this->redirectToRoute('home');
        }
    }

    /**
     * @Route("/delete/section/{id}", name="deletesection")
     */
    public function deletesection(Section $section,Request $request,RessourceRepository $ressourcerepository)
    {       $user=$this->getUser();
            if($user){
            $idsection=$section->getId();
            $ressourcesection=$ressourcerepository->findOneBy(['idsection'=>$idsection]);
            $entityManager = $this->getDoctrine()->getManager();
            while ($ressourcesection) {
                    $entityManager->remove($ressourcesection);
                    $entityManager->flush();
                    $ressourcesection=$ressourcerepository->findOneBy(['idsection'=>$idsection]);

            }
            $entityManager->remove($section);
            $entityManager->flush();
            return $this->redirectToRoute('showformation'); 
            }
            else{
                return $this->redirectToRoute('home'); 
            }
    }
        /**
     * @Route("/show/{idformation}/section", name="showsection")
     */

     public function showsection(SectionRepository $sectionrepository,$idformation)
    {   $user=$this->getUser();
        if($user){ 
        $section = $sectionrepository->findBy(['idformation' => $idformation]);
        
        return $this->render('section/showsection.html.twig', [
            'sections' => $section
        ]);
        }
        else{
            return $this->redirectToRoute('home');
        }
    }
}
