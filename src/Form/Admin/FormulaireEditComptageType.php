<?php

namespace App\Form\Admin;

use App\Entity\Departement;
use App\Form\Comptage\FormationType;
use App\Form\Comptage\PorteType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Formulaire;

class FormulaireEditComptageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('portes', CollectionType::class, [
            'entry_type' => PorteType::class,
            'required' => true,
            'allow_add' => true,
            'by_reference' => false,
            'allow_delete' => true,
            'prototype' => true,
            'attr' => [
                'class' => 'portes',
            ],
        ]);

        $builder->add('formations', CollectionType::class, [
            'entry_type' => FormationType::class,
            'required' => true,
            'allow_add' => true,
            'by_reference' => false,
            'allow_delete' => true,
            'prototype' => true,
            'attr' => [
                'class' => 'formations',
            ],
        ]);

        $builder->add('Submit', SubmitType::class, [
            'label' => 'Editer le formulaire',
            'attr' => ['class' => 'btn-success'], ]);
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