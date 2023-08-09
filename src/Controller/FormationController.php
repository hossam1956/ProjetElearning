<?php

namespace App\Controller;
use App\Entity\Formation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\SectionRepository;
use App\Repository\FormationRepository;
use App\Repository\FormationUserRepository;
use App\Repository\RessourceRepository;
use App\Repository\InscriptionFormationRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Form\FormationFormType;
use App\Service\Avancement;





class FormationController extends AbstractController
{
    /**
     * @Route("/formation/add", name="addformation")
     */
    public function addformation(Request $request)
    {   $user=$this->getUser();
        $role=$user->getRole();
        if($user && $role == "formateur"){
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
    {   
        $user=$this->getUser();
        $role=$user->getRole();
        if($user && $role == "formateur"){
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
    public function deleteformation(Formation $formation,Request $request,SectionRepository $sectionrepository,RessourceRepository $ressourcerepository,InscriptionFormationRepository $inscriptionrepository)
    {       
        $user=$this->getUser();
        $role=$user->getRole();
        if($user && $role == "formateur"){

        
            $idformation=$formation->getId();
            $sectionformation=$sectionrepository->findOneBy(['idformation'=>$idformation]);
            $inscriptionformations=$inscriptionrepository->findBy(['IdFormation'=>$idformation]);
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
            //----inscription
            if ($inscriptionformations) {
                    foreach($inscriptionformations as $inscriptionformation){
                        $entityManager->remove($inscriptionformation);
                        $entityManager->flush();
                    }     

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

     public function showformation(FormationRepository $formationrepository, Avancement $avancement,InscriptionFormationRepository $inscriptionrepository)
    {       
            $user=$this->getUser();
            $role=$user->getRole();
            
            if($user && $role !="user"){
            
            $formation = $formationrepository->findAll();
            $avancement_values = [];
            foreach ($formation as $uneformation) {
                array_push($avancement_values, $avancement->GetUserAvancement($uneformation->getId(), $this->getUser()->getId()));
            }
            return $this->render('formation/showformation.html.twig', [
                'formation' => $formation,
                'avancement_values' => $avancement_values
            ]);
        }
        elseif($user && $role =="user"){
            $formation_ids=[];
            $iduser=$user->getId();
            $inscriptions=$inscriptionrepository->findBy(['IdUser' => $iduser]);
            foreach($inscriptions as $inscription){
                $formations=$inscription->getIdFormation();
                $formation_ids[$formations->getId()]=$formations->getId();
            }
            
            $formation = $formationrepository->findBy(['id' => $formation_ids]);
            $avancement_values = [];
            foreach ($formation as $uneformation) {
                array_push($avancement_values, $avancement->GetUserAvancement($uneformation->getId(), $this->getUser()->getId()));
            }
            return $this->render('formation/showformation.html.twig', [
                'formation' => $formation,
                'avancement_values' => $avancement_values
            ]);

        }
        else{
            return $this->redirectToRoute('home'); 
        }
        
    }

    /**
     * @Route("/search", name="search_form")
     */
    public function searchForm(Request $request,Avancement $avancement)
    {   

        $query = $request->query->get('query');

        $formations = $this->getDoctrine()->getRepository(Formation::class)->findByTitre($query);

        $avancement_values = [];
        foreach ($formations as $formation) {
            array_push($avancement_values, $avancement->GetUserAvancement($formation->getId(), $this->getUser()->getId()));
        }

        return $this->render('formation/showformation.html.twig', [
            'formation' => $formations,
            'query' => $query,
            'avancement_values' => $avancement_values
        ]);
        

    }


}
