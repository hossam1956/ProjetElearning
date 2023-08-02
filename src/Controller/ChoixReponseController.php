<?php

namespace App\Controller;

use App\Entity\ChoixReponse;
use App\Entity\Question;
use App\Form\ChoixReponseType;
use App\Repository\ChoixReponseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;

class ChoixReponseController extends AbstractController
{

    private $choixRepository;
    private $flashMessage;

    public function __construct(ChoixReponseRepository $choixRepository, FlashBagInterface $flashMessage)
    {
        $this->choixRepository = $choixRepository;
        $this->flashMessage = $flashMessage;
    }

    // #[Route('/choix', name: 'app_choix')]
    // public function index()
    // {
    //     $choix = $this->choixRepository->findAll();
    //     return $this->render('choix_reponse/index.html.twig', [
    //         "choix" => $choix,
    //     ]);
    // }
    /**
     * @Route("/{question_id}/choix", name="app_choix")
     */
    public function listChoixParQuestion($question_id)
    {
        $entityManager = $this->getDoctrine()->getManager();

        // Find the Exercise entity with ID 1 (you can replace 1 with the ID of the desired exercise)
        $question = $entityManager->getRepository(Question::class)->find($question_id);

        // Get all the questions associated with the exercise
        $choix = $question->getChoixReponses();

        // $choixs = $this->choixRepository->find($choix_id)->;
        return $this->render('choix_reponse/index.html.twig', [
            "choix" => $choix,
            'question_id' => $question_id
        ]);
    }
    /**
     * @Route("/{question_id}/choix/add", name="app_choix.add")
     */
    public function addChoix(Request $request, $question_id)
    {
        $choix = new ChoixReponse();
        $form = $this->createForm(ChoixReponseType::class, $choix);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$choix` variable has also been updated
            $choix = $form->getData();
            // $user = $this->getUser();


            $entityManager = $this->getDoctrine()->getManager();
            $question = $entityManager->getRepository(Question::class)->find($question_id);
            $choix->setQuestion($question);

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            $entityManager->persist($choix);
            $entityManager->flush();
            $this->flashMessage->add("success", "Choix ajouté !");

            return $this->redirectToRoute(
                'app_choix',
                ["question_id" => $question_id]
            );
        }

        return $this->render('choix_reponse/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/{question_id}/choix/edit/{id}", name="app_choix.edit")
     */
    public function editChoix(ChoixReponse $choix, Request $request, $question_id)
    {
        $form = $this->createForm(ChoixReponseType::class, $choix);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $choix = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($choix);
            $entityManager->flush();
            $this->flashMessage->add("success", "Choix modifié !");

            return $this->redirectToRoute(
                'app_choix',
                ['question_id' => $question_id]
            );
        }

        return $this->render('choix_reponse/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/{question_id}/choix/delete/{id}", name="app_choix.delete")
     */
    public function deleteChoix(ChoixReponse $choix, $question_id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($choix); // delete the choix
        $entityManager->flush(); // mettre à jour la db
        $this->flashMessage->add("success", "Choix supprimée!");

        return $this->redirectToRoute(
            'app_choix',
            ["question_id" => $question_id]
        );
    }
}
