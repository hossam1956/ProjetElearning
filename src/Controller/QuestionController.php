<?php

namespace App\Controller;

use App\Entity\Exercice;
use App\Entity\Question;
use App\Form\PracticeType;
use App\Form\QuestionType;
use App\Repository\QuestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/{exercice_id}')]
class QuestionController extends AbstractController
{
    private $questionRepository;
    private $flashMessage;

    public function __construct(QuestionRepository $questionRepository, FlashBagInterface $flashMessage)
    {
        $this->questionRepository = $questionRepository;
        $this->flashMessage = $flashMessage;
    }

    // #[Route('/question', name: 'app_question_list')]
    // public function index()
    // {
    //     $questions = $this->questionRepository->findAll();
    //     return $this->render('question/index.html.twig', [
    //         "questions" => $questions,
    //     ]);
    // }

    #[Route('/questions', name: 'app_question')]
    public function listQuestionsParExercice($exercice_id)
    {
        $entityManager = $this->getDoctrine()->getManager();

        // Find the Exercise entity with ID 1 (you can replace 1 with the ID of the desired exercise)
        $exercice = $entityManager->getRepository(Exercice::class)->find($exercice_id);

        // Get all the questions associated with the exercise
        $questions = $exercice->getQuestions();

        // $questions = $this->questionRepository->find($exercice_id)->;
        return $this->render('question/index.html.twig', [
            "questions" => $questions,
            'exercice_id' => $exercice_id
        ]);
    }

    #[Route('/question/show/{id}', name: 'app_question.show')]
    public function showQuestion($id, $exercice_id)
    {
        $question = $this->questionRepository->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        // $exercice = $entityManager->getRepository(Exercice::class)->find($exercice_id);
        // $exercice = $entityManager->getRepository(Exercice::class)->find($exercice_id);


        if (!$question) {
            throw $this->createNotFoundException('No question found for id ' . $id);
        }

        return $this->render('question/show.html.twig', [
            "question" => $question,
            "exercice_id" => $exercice_id
            // "user" => $question->getUser()->getUsername()
        ]);
    }

    #[Route('/question/add', name: 'app_question.add')]
    public function addQuestion(Request $request, $exercice_id)
    {
        $question = new Question();
        $form = $this->createForm(QuestionType::class, $question);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$question` variable has also been updated
            $question = $form->getData();
            // $user = $this->getUser();

            $entityManager = $this->getDoctrine()->getManager();

            // Find the Exercise entity with ID 1 (you can replace 1 with the ID of the desired exercise)
            $exercice = $entityManager->getRepository(Exercice::class)->find($exercice_id);
            $question->setExercice($exercice);

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            $entityManager->persist($question);
            $entityManager->flush();
            $this->flashMessage->add("success", "Question added, now let's add some choices!");

            return $this->redirectToRoute(
                'app_question',
                ['exercice_id' => $exercice_id]
            );
        }

        // $exercice_id = $exercice->getId();

        // Get all the questions associated with the exercise
        return $this->render('question/add.html.twig', [
            'form' => $form->createView(),
            'exercice_id' => $exercice_id
        ]);
    }

    #[Route('/question/edit/{id}', name: 'app_question.edit')]
    public function editQuestion(Question $question, Request $request, $exercice_id)
    {
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $question = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($question);
            $entityManager->flush();
            $this->flashMessage->add("success", "question modifié !");

            return $this->redirectToRoute(
                'app_question',
                ['exercice_id' => $exercice_id]
            );
        }

        return $this->render('question/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/question/delete/{id}', name: 'app_question.delete')]
    public function deleteQuestion(Question $question, $exercice_id)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $choix = $question->getChoixReponses();
        foreach ($choix as $choice) {
            $entityManager->remove($choice);
        }

        $entityManager->remove($question); // delete the question
        $entityManager->flush(); // mettre à jour la db
        $this->flashMessage->add("success", "Question supprimée !");

        return $this->redirectToRoute(
            'app_question',
            ['exercice_id' => $exercice_id]
        );
    }

    #[Route('/practice/{question_id}', name: 'app_exercice.practice')]
    public function PracticeExercice(Request $request, $question_id)
    {
        $form = $this->createForm(PracticeType::class, null, ['question_id' => $question_id]);

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

        return $this->render('exercice/practice.html.twig', [
            'form' => $form->createView(),

        ]);
    }
}
