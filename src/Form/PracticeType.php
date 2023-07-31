<?php

namespace App\Form;

use App\Repository\QuestionRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PracticeType extends AbstractType
{

    // private $question;
    // private $choix;
    // private $question_id;
    private $questionRepository;

    public function __construct(QuestionRepository $questionRepository)
    {
        $this->questionRepository = $questionRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $question_id = $options['question_id'];

        $question = $this->questionRepository->find($question_id);
        // $question_id = $question_id;
        // $choix = [];
        // $choices = $question->getChoixReponses();
        // foreach ($choices as $choice) {
        //     array_push($choix, $choice);
        // }

        $choices = $question->getChoixReponses();
        $choix = [];
        $i = 0;
        foreach ($choices as $choice) {
            $choix[$i] = $choice->getChoix();
        }
        dump($choix);

        $builder
            // ... other form fields ...
            ->add('choix', ChoiceType::class, [
                'choices' => $choix,
                'expanded' => true, // This will render the radio buttons instead of a select dropdown
                'multiple' => false, // Set this to false to render radio buttons
                'label' => 'Choose an option:', // The label for the field
                // Other options if needed
            ]);
    }



    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('question_id'); // Define 'question_id' as a required option
        // $resolver->setAllowedTypes('question_id', 'int'); // Ensure 'question_id' is of type integer
    }

    // public function configureOptions(OptionsResolver $resolver)
    // {
    //     $resolver->setRequired('question_id'); // Define 'question_id' as a required option
    //     $resolver->setAllowedTypes('question_id', 'int'); // Ensure 'question_id' is of type integer
    //     $resolver->setDefaults([
    //         'data_class' => null, // Set data_class to null to avoid trying to map the form to an entity
    //     ]);
    // }

    // ...
}





// use Symfony\Component\Form\AbstractType;
// use Symfony\Component\Form\Extension\Core\Type\CollectionType;
// use Symfony\Component\Form\Extension\Core\Type\TextType;
// use Symfony\Component\Form\FormBuilderInterface;
// use Symfony\Component\OptionsResolver\OptionsResolver;

// class ExerciseType extends AbstractType
// {
//     public function buildForm(FormBuilderInterface $builder, array $options)
//     {
//         $builder
//             ->add('questions', CollectionType::class, [
//                 'entry_type' => QuestionType::class,
//                 'allow_add' => true,
//                 'prototype' => true,
//                 'by_reference' => false,
//             ]);
//     }

//     public function configureOptions(OptionsResolver $resolver)
//     {
//         $resolver->setDefaults([
//             'data_class' => YourExerciseEntity::class,
//         ]);
//     }
// }

// // src/Form/QuestionType.php

// namespace App\Form;

// use Symfony\Component\Form\AbstractType;
// use Symfony\Component\Form\Extension\Core\Type\CollectionType;
// use Symfony\Component\Form\Extension\Core\Type\TextType;
// use Symfony\Component\Form\FormBuilderInterface;
// use Symfony\Component\OptionsResolver\OptionsResolver;

// class QuestionType extends AbstractType
// {
//     public function buildForm(FormBuilderInterface $builder, array $options)
//     {
//         $builder
//             ->add('content', TextType::class)
//             ->add('choices', CollectionType::class, [
//                 'entry_type' => ChoiceType::class,
//                 'allow_add' => true,
//                 'prototype' => true,
//                 'by_reference' => false,
//             ]);
//     }

//     public function configureOptions(OptionsResolver $resolver)
//     {
//         $resolver->setDefaults([
//             'data_class' => YourQuestionEntity::class,
//         ]);
//     }
// }

// // src/Form/ChoiceType.php
