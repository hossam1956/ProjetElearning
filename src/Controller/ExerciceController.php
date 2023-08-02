<?php

namespace App\Controller;

use App\Entity\Exercice;
use App\Form\ExerciceType;
use App\Form\PracticeType;
use App\Repository\ExerciceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ExerciceController extends AbstractController
{
    private $exerciceRepository;
    private $flashMessage;

    public function __construct(ExerciceRepository $exerciceRepository, FlashBagInterface $flashMessage)
    {
        $this->exerciceRepository = $exerciceRepository;
        $this->flashMessage = $flashMessage;
    }

    /**
     * @Route("/exercice", name="app_exercice")
     */
    public function index()
    {
        $exercices = $this->exerciceRepository->findAll();
        return $this->render('exercice/index.html.twig', [
            "exercices" => $exercices,
        ]);
    }
    /**
     * @Route("/exercice/show/{id}", name="app_exercice.show")
     */
    
    public function showExercice($id)
    {
        $exercice = $this->exerciceRepository->find($id);

        if (!$exercice) {
            throw $this->createNotFoundException('No exercice found for id ' . $id);
        }

        // dd($exercice->getQuestions());

        return $this->render('exercice/show.html.twig', [
            "exercice" => $exercice,
            // "user" => $exercice->getUser()->getUsername()
        ]);
    }
     /**
     * @Route("/exercice/add", name="app_exercice.add")
     */
 
    public function addExercice(Request $request)
    {
        $exercice = new Exercice();
        $form = $this->createForm(ExerciceType::class, $exercice);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$exercice` variable has also been updated
            $exercice = $form->getData();
            // $user = $this->getUser();
            // $exercice->setUser($user);

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($exercice);
            $entityManager->flush();
            $this->flashMessage->add("success", "Exercise added, now let's add some questions!");

            return $this->redirectToRoute('app_exercice');
        }

        return $this->render('exercice/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
     /**
     * @Route("/exercice/edit/{id}", name="app_exercice.edit")
     */
    public function editExercice(Exercice $exercice, Request $request)
    {
        $form = $this->createForm(ExerciceType::class, $exercice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $exercice = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($exercice);
            $entityManager->flush();
            $this->flashMessage->add("success", "exercice modifié !");

            return $this->redirectToRoute('app_exercice');
        }

        return $this->render('exercice/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/exercice/delete/{id}", name="app_exercice.delete")
     */
    public function deleteExercice(Exercice $exercice)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $questions = $exercice->getQuestions();

        foreach ($questions as $question) {
            $choix = $question->getChoixReponses();
            foreach ($choix as $choice) {
                $entityManager->remove($choice);
            }
            $entityManager->remove($question);
        }

        $entityManager->remove($exercice); // delete the exercice
        $entityManager->flush(); // mettre à jour la db
        $this->flashMessage->add("success", "Exercice supprimée !");

        return $this->redirectToRoute('app_exercice');
    }
    /**
     * @Route("/exercice/practice/{exercice_id}", name="app_exercice.practice")
     */
    public function PracticeExercice(Request $request, $exercice_id, SessionInterface $session)
    {
        $form = $this->createForm(PracticeType::class, null, ['exercice_id' => $exercice_id]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $response = $form->getData();
            $session->set('reponse', $response);
            // dd($session->get('reponse'));

            $this->flashMessage->add("success", "Vos réponse sont bien enregistrées!");
            $exercice = $this->exerciceRepository->find($exercice_id);
            // dd($exercice);
            // dump($exercice);
            $questions = $exercice->getQuestions();

            $score = 0;
            $number_of_questions = count($questions);
            $i = 1;
            foreach ($questions as $question) {
                if ($response["choix" . $i] == $question->getReponse()) {
                    $score += 100 / $number_of_questions;
                    // echo "hi";
                }
                $i++;
                // echo $response["choix" . $i] . "<br />";
                // echo $question->getReponse() . "<br />";
                // echo '----------------------------------<br />';
            }

            // print_r($response);

            // echo $score;
            // dump($response);

            return $this->redirectToRoute(
                'app_exercice.result',
                [
                    'score' => $score,
                    'exercice_id' => $exercice_id,
                    // 'reponse' => $response
                ]
            );
        }

        return $this->render('exercice/practice.html.twig', [
            'form' => $form->createView(),
        ]);
    }
     /**
     * @Route("/exercice/{exercice_id}/resultat/{score}", name="app_exercice.result")
     */
    public function result($score, $exercice_id)
    {
        $exercice = $this->exerciceRepository->find($exercice_id);

        return $this->render(
            'exercice/result.html.twig',
            [
                'score' => $score,
                'exercice' => $exercice,
                // 'reponse' => $response
            ]
        );
    }













    // #[Route('/practice/{id}', name: 'app_exercice.practice')]
    // public function practiceExercice($id)
    // {
    //     $exercice = $this->exerciceRepository->find($id);

    //     if (!$exercice) {
    //         throw $this->createNotFoundException('No exercice found for id ' . $id);
    //     }

    //     return $this->render('exercice/practice.html.twig', [
    //         "exercice" => $exercice,
    //         // "user" => $exercice->getUser()->getUsername()
    //     ]);
    // }

    // #[Route('/practice/{id}', name: 'app_exercice.practice')]
    // public function practiceExercice($id)
    // {
    //     $exercice = $this->exerciceRepository->find($id);

    //     if (!$exercice) {
    //         throw $this->createNotFoundException('No exercice found for id ' . $id);
    //     }

    //     $form = $this->createForm(PracticeType::class, null, ['question_id' => $questionId]);

    //     $form->handleRequest($request);
    //     if ($form->isSubmitted() && $form->isValid()) {
    //         // $form->getData() holds the submitted values
    //         // but, the original `$exercice` variable has also been updated
    //         $exercice = $form->getData();
    //         // $user = $this->getUser();
    //         // $exercice->setUser($user);

    //         // ... perform some action, such as saving the task to the database
    //         // for example, if Task is a Doctrine entity, save it!
    //         $entityManager = $this->getDoctrine()->getManager();
    //         $entityManager->persist($exercice);
    //         $entityManager->flush();
    //         $this->flashMessage->add("success", "Exercise added, now let's add some questions!");

    //         return $this->redirectToRoute('app_exercice');
    //     }

    //     return $this->render('exercice/practice.html.twig', [
    //         "exercice" => $exercice,
    //         // "user" => $exercice->getUser()->getUsername()
    //     ]);
    // }
}
