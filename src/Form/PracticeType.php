<?php

namespace App\Form;

use App\Repository\ExerciceRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PracticeType extends AbstractType
{
    private $exerciceRepository;

    public function __construct(ExerciceRepository $exerciceRepository)
    {
        $this->exerciceRepository = $exerciceRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $exercice_id = $options['exercice_id'];
        $exercice = $this->exerciceRepository->find($exercice_id);
        $questions = $exercice->getQuestions();

        $j = 1;
        foreach ($questions as $question) {
            $choix = [[]];
            $choices = $question->getChoixReponses();
            $i = 1;
            foreach ($choices as $choice) {
                $choix[$j][$choice->getChoix()] = "$i";
                $i++;
            }
            $builder->add(
                'choix' . $j,
                ChoiceType::class,
                [
                    'choices' => $choix,
                    'expanded' => true,
                    'multiple' => false,
                    'label' => $question->getQuestion(),
                ]
            );
            $j++;
        }

        $builder->add('valider', SubmitType::class);
    }



    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('exercice_id');
        // $resolver->setRequired('question_id'); // Define 'question_id' as a required option
        // $resolver->setAllowedTypes('question_id', 'int'); // Ensure 'question_id' is of type integer
    }
}
