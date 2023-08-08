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
use App\Service\Avancement;

class FormationController extends AbstractController
{
    /**
     * @Route("/formation/add", name="addformation")
     */
    public function addformation(Request $request)
    {
        $formation = new Formation;
        $form = $this->createForm(FormationFormType::class, $formation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $formation = $form->getData();
            //image upload
            $image = $form['image']->getData();
            $image_name = $image->getClientOriginalName();
            $image->move($this->getParameter("photo_directory"), $image_name);
            $formation->setImage($image_name);
            //..........
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($formation);
            $entityManager->flush();

            return $this->redirectToRoute('showformation');
        }

        return $this->render('formation/index.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/edit/formation/{id}", name="editformation")
     */
    public function editformation(Formation $formation, Request $request)
    {
        $formation->setImage(null);
        $form = $this->createForm(FormationFormType::class, $formation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $formation = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($formation);
            $entityManager->flush();

            return $this->redirectToRoute('showformation');
        }
        return $this->render('formation/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/formation/{id}", name="deleteformation")
     */
    public function deleteformation(Formation $formation, Request $request, SectionRepository $sectionrepository, RessourceRepository $ressourcerepository)
    {
        $idformation = $formation->getId();
        $sectionformation = $sectionrepository->findOneBy(['idformation' => $idformation]);
        $entityManager = $this->getDoctrine()->getManager();
        while ($sectionformation) {
            $idsection = $sectionformation->getId();
            $ressourcesection = $ressourcerepository->findOneBy(['idsection' => $idsection]);
            while ($ressourcesection) {
                $entityManager->remove($ressourcesection);
                $entityManager->flush();
                $ressourcesection = $ressourcerepository->findOneBy(['idsection' => $idsection]);
            }
            $entityManager->remove($sectionformation);
            $entityManager->flush();
            $sectionformation = $sectionrepository->findOneBy(['idformation' => $idformation]);
        }
        $entityManager->remove($formation);
        $entityManager->flush();
        return $this->redirectToRoute('showformation');
    }


    /**
     * @Route("/show/formation", name="showformation")
     */

    public function showformation(FormationRepository $formationrepository, Avancement $avancement)
    {
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

    /**
     * @Route("/search", name="search_form")
     */
    public function searchForm(Request $request)
    {
        $query = $request->query->get('query');

        $formations = $this->getDoctrine()->getRepository(Formation::class)->findByTitre($query);

        return $this->render('formation/showformation.html.twig', [
            'formation' => $formations,
            'query' => $query,
        ]);
    }
}
