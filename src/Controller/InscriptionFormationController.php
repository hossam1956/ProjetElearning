<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\FormationRepository;
use App\Repository\UserRepository;
use App\Repository\InscriptionFormationRepository;
use App\Entity\InscriptionFormation;

class InscriptionFormationController extends AbstractController
{
    /**
     * @Route("formation/{idformation}/inscription/{idformateur}", name="app_inscription_formation")
     */
    public function index(UserRepository $userrepository,FormationRepository $formationrepository,InscriptionFormationRepository $inscriptionformationrepository,$idformateur,$idformation)
    {   $users=$userrepository->findBy(['role'=>'user']);
        $exist_user=null;
        $user_available=[];
        foreach ($users as $user) {
            $exist_user=$inscriptionformationrepository->findBy(['IdUser'=>$user->getId(),'IdFormation'=>$idformation]);
            if ($exist_user == null){
                $user_available[$user->getId()]=$user;
            }
            $exist_user=null;

        }
        return $this->render('inscription_formation/index.html.twig', [
            'user' => $user_available
        ]);
        
        
    }
    /**
     * @Route("formation/{idformation}/inscription/{idformateur}/true/{iduser}", name="app_inscription_formation_true")
     */
    public function indextrue(UserRepository $userrepository,FormationRepository $formationrepository,InscriptionFormationRepository $inscriptionformationrepository,$idformateur,$idformation,$iduser)
    {   $user=$this->getUser();
        if($user){
        $formation=$formationrepository->findOneBy(['id'=>$idformation]);
        $user=$userrepository->findOneBy(['id'=>$iduser]);
        $foramteur=$userrepository->findOneBy(['id'=>$idformateur]);
        $inscription = new InscriptionFormation();
        $inscription->setIdFormation($formation);
        $inscription->setIdFormateur($foramteur);
        $inscription->setIdUser($user);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($inscription);
        $entityManager->flush();
        return $this->redirectToRoute('showformation');
        }
        else{
            return $this->redirectToRoute('home');
        }
        
        
    }
}
