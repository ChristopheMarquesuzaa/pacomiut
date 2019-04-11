<?php

namespace App\Form\Admin;

use App\Entity\Departement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DepartementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Libellé long du département',
            ])->add('shortname', TextType::class, [
                'label' => 'Libellé court du département',
            ])->add('Submit', SubmitType::class, [
                'label' => 'Ajouter le département',
                'attr' => ['class' => 'btn-success'], ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'departement_class' => Departement::class,
        ]);
    }
}
