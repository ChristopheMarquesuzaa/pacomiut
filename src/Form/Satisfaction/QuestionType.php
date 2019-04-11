<?php

namespace App\Form\Satisfaction;

use App\Entity\Formulaire;
use App\Entity\Satisfaction\Question;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, [
            'label' => 'Intitulé de la question',
        ]);

        $builder->add('type', ChoiceType::class, [
            'label' => 'Quel est le type de la question ?',
            'choices' => [
                'Zone de texte court (1 seule ligne)' => Question::TYPE_INPUT,
                'Zone de texte long (1 paragraphe)' => Question::TYPE_TEXTAREA,
                'Question à choix unique (Liste)' => Question::TYPE_SELECT_UNIQUE,
                'Question à choix unique (Boutons radio)' => Question::TYPE_RADIO_BUTTON,
                'Question à choix multiple (Liste)' => Question::TYPE_SELECT_MULTIPE,
                'Question à choix multiple (Cases à cocher)' => Question::TYPE_CHECKBOX,
            ],
            'multiple' => false,
            'expanded' => true,
            'required' => true,
            'data' => Question::TYPE_INPUT,
        ]);

        $builder->add('answer', TextType::class, [
            'label' => 'Si la question possède une ou des réponses prédéfinies, indiquer la (les) réponse(s) possible(s) en la (les) séparant par un \'' . Formulaire::SEPARATEUR . '\'',
            'required' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'QuestionType';
    }
}
