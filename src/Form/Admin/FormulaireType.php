<?php

namespace App\Form\Admin;

use App\Entity\Departement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormulaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du formulaire',
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Comptage' => 0,
                    'Enquête' => 1,
                ],
                'multiple' => false,
                'expanded' => true,
            ])
            ->add('departement', EntityType::class, [
                'label' => 'Département',
                'class' => Departement::class,
                'choice_label' => 'name',
            ])
            ->add('actif', ChoiceType::class, [
                'choices' => [
                    'Oui' => 1,
                    'Non' => 0,
                ],
                'multiple' => false,
                'expanded' => true,
            ])
            ->add('questions', TextareaType::class)
            ->add('Submit', SubmitType::class, [
                'label' => 'Ajouter le formulaire',
                'attr' => ['class' => 'btn-success'], ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'formulaire_class' => Departement::class,
        ]);
    }

    public function setDefaultOptions(\Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'allow_extra_fields' => true,
        ]);
    }
}
