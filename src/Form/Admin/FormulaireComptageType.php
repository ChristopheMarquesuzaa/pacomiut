<?php

namespace App\Form\Admin;

use App\Entity\Departement;
use App\Entity\Formulaire;
use App\Form\Comptage\FormationType;
use App\Form\Comptage\PorteType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormulaireComptageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du formulaire',
            ])
            ->add('departement', EntityType::class, [
                'label' => 'Département',
                'class' => Departement::class,
                'choice_label' => 'name',
            ])
            ->add('actif', ChoiceType::class, [
                'label' => 'Actif ? (le formulaire va t\'il être le formulaire par default pour ce département)',
                'multiple' => false,
                'expanded' => true,
                'required' => true,
                'data' => true,
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
            ])
            ->add('accompagnateur', IntegerType::class, [
                'label' => 'Nombre d\'accompagnateur par default sur le formulaire',
                'data' => 0,
            ]);

        $builder->add('portes', CollectionType::class, [
            'entry_type' => PorteType::class,
            'required' => true,
            'allow_add' => true,
            'allow_delete' => true,
            'prototype' => true,
            'required' => true,
            'attr' => [
                'class' => 'portes',
            ],
        ]);

        $builder->add('formations', CollectionType::class, [
            'entry_type' => FormationType::class,
            'required' => true,
            'allow_add' => true,
            'allow_delete' => true,
            'prototype' => true,
            'required' => true,
            'attr' => [
                'class' => 'formations',
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
