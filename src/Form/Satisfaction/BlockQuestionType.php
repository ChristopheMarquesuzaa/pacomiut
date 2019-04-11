<?php

namespace App\Form\Satisfaction;

use App\Entity\Satisfaction\Block;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlockQuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('questions', CollectionType::class, [
            'entry_type' => QuestionType::class,
            'label' => 'Ajouter des questions au bloc de questions.',
            'allow_add' => true,
            'allow_delete' => true,
            'prototype' => true,
            'required' => true,
            'attr' => [
                'class' => 'questions',
            ],
        ]);

        $builder->add('Submit', SubmitType::class, [
            'label' => 'Ajouter les questions',
            'attr' => ['class' => 'btn-primary'], ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'formulaire_class' => Block::class,
        ]);
    }

    public function setDefaultOptions(\Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'allow_extra_fields' => true,
        ]);
    }
}
