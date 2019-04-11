<?php

namespace App\Form\Comptage;

use App\Entity\Comptage\Formation;
use App\Entity\Comptage\Porte;
use App\Entity\Comptage\Visiteur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VisiteurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $formulaire = $options['data']->getFormulaire();
        $builder
            ->add('porte', EntityType::class, [
                'class' => Porte::class,
                'choices' => $formulaire->getPortes(),
                'multiple' => false,
                'expanded' => true,
                'data' => $formulaire->getPortes()[0],
            ])
            ->add('formations', EntityType::class, [
                'class' => Formation::class,
                'choices' => $formulaire->getFormations(),
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('accompagnateur', IntegerType::class, [
                'label' => 'accompagnateur(s)',
                'data' => $formulaire->getAccompagnateur(),
            ])
            ->add('Submit', SubmitType::class, [
                'label' => 'Ajouter le visiteur',
                'attr' => ['class' => 'btn-success'], ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'visiteur_class' => Visiteur::class,
        ]);
    }
}
