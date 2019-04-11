<?php

namespace App\Form\Admin;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['data']->getRoles()[0] == "ROLE_ADMIN") {
            $role = 'admin';
        } else if ($options['data']->getRoles()[0] == "ROLE_PROF") {
            $role = 'enseignant';
        } else {
            $role = 'etudiant';
        }
        $builder
            ->add('email')
            ->add('username')
            ->add('firstname')
            ->add('surname')
            ->add('roles', ChoiceType::class, [
                'mapped' => false,
                'data' => $role,
                'choices' => [
                    'Etudiant' => 'etudiant',
                    'Enseignant' => 'enseignant',
                    'Admin' => 'admin'
                ],
                'multiple' => false,
                'expanded' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
