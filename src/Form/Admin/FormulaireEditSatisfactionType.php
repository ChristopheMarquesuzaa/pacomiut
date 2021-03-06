<?php

namespace App\Form\Admin;

use App\Entity\Departement;
use App\Entity\Formulaire;
use App\Form\Satisfaction\BlockType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormulaireEditSatisfactionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('blocks', CollectionType::class, [
            'entry_type' => BlockType::class,
            'label' => 'Ajouter ou supprimer des blocs de questions.',
            'allow_add' => true,
            'allow_delete' => true,
            'prototype' => true,
            'required' => false,
            'attr' => [
                'class' => 'block',
            ],
        ]);

        $builder->add('Submit', SubmitType::class, [
            'label' => 'Ajouter le formulaire',
            'attr' => ['class' => 'btn-primary'], ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'formulaire_class' => Formulaire::class,
        ]);
    }

    public function setDefaultOptions(\Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'allow_extra_fields' => true,
        ]);
    }
}
