<?php

namespace App\Form\Formulaire;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class FormInfoGIMType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('formations', ChoiceType::class, [
            'choices' => [
                'Infomatique' => [
                    'DUT Informatique' => 'DUT Informatique',
                    'LP Métiers du Numérique' => 'LP Métiers du Numérique',
                    'LP Programmation Avancée' => 'LP Programmation Avancée',
                    'DU Technologies de l\'Information et de la Communication' => 'DU TIC', ],
                'GIM' => [
                    'DUT GIM' => 'DUT GIM',
                    'LP Ecologie Industrielle' => 'LP Ecologie Industrielle',
                    'LP Geo 3D' => 'LP Geo 3D',
                    'LP Ingénierie des Façades' => 'LP Ingénierie des Façades', ], ],
                'multiple' => true,
                'expanded' => true,
        ])
            
        ->add('sexe', ChoiceType::class, [
                'choices' => [
                    'Homme' => true,
                    'Femme' => false,
                ],
                'expanded' => true,
                'multiple' => false,
                'label' => 'Genre',
            ])
            
        ->add('age', IntegerType::class, [
                'label' => 'Age',
                'attr' => ['value' => 18,],
            ])
            
        ->add('type', ChoiceType::class, [
                'label' => 'Vous êtes :',
                'choices' => [
                    'Elève/Etudiant' => 'eleve',
                    'Parent/Accompagnateur' => 'parent_accompagnateur',
                ],
                'expanded' => true,
                'multiple' => false,
            ])

            ->add('departement', TextType::class, [
                'label' => 'Département',
            ])

            ->add('ville', TextType::class, [
                'label' => 'Ville',
            ])

            ->add('ecole', TextType::class, [
                'label' => 'Lycée/Etablissement',
            ])

            ->add('formation', ChoiceType::class, [
                'choices' => [
                    'Seconde, première' => 'seconde_premiere',
                    'Terminale' => 'terminale',
                    'BTS' => 'bts',
                    'DUT' => 'dut',
                    'Licence (L1, L2 ou L3)' => 'licence',
                    'CPGE' => 'cpge',
                    'Autre' => 'autre',
                ],
                'expanded' => true,
                'multiple' => false,
                'label' => 'Formation actuelle',
            ])
            
            ->add('motifs', ChoiceType::class, [
                'attr'=> [
                    'id'=>'motifs_form',
                ],
                'expanded' => true,
                'multiple' => true,
                'label' => 'Quel est le motif de votre visite',
                'choices' => [
                    'Rencontrer des enseignants' => 'rencontrer_enseignants',
                    'Rencontrer des étudiants' => 'rencontrer_etudiants',
                    'Visiter l’établissement' => 'visiter',
                    'En savoir plus sur les formations de l’IUT' => 'informations_iut',
                    'En savoir plus sur le logement, les bourses' => 'informations_bourses',
                ],
            ])
            
            ->add('motifsAutre', TextareaType::class, [
                'label' => 'Autre motif',
                'required'  => false,
            ])

            ->add('provenances', ChoiceType::class, [
                'expanded' => true,
                'multiple' => true,
                'label' => 'Comment avez-vous entendu parler
                            de la journée portes ouvertes ?',
                'choices' => [
                    'Forum des métiers de votre lycée' => 'forum',
                    'Salons (Studyrama, InfoSup...)'=> 'salon',
                    'Etablissement d’origine' => 'Etablissement',
                    'Entourage/Proches' => 'proche',
                    'Etudiant actuel/ancien' => 'etudiant_actuel/ancien',
                    'Sud-Ouest' => 'Sud-ouest',
                    'UrbanTV' => 'UrbanTV',
                    'Magazines Communaux' => 'Magazines-Communaux',
                    'Radio' => 'Radio',
                    'Internet (Onisep...)' => 'internet',
                    'Site Web de l’IUT' => 'site',
                    'Page Facebook de l\'IUT' => 'facebook',
                    'Page LinkedIn de l\'IUT' => 'linkedin',
                ],
            ])

            ->add('provenancesAutre', TextareaType::class, [
                'label' => 'Autre provenance',
                'required'  => false,
            ])

            ->add('qualite', ChoiceType::class, [
                
                'label' => 'Les informations présentées vous
                            ont paru (1=Médiocres, 4=Très Bonnes) ',
                'choices' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                ],
                'expanded' => true,
                'multiple' => false,
            ])

            ->add('utile', ChoiceType::class, [
                'label' => 'Cette journée vous a-t-elle
                            été utile pour vos choix ?',
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'expanded' => true,
                'multiple' => false
            ])
            
            ->add('candidat', ChoiceType::class, [
                'label' => 'Pensez-vous postuler pour une de nos formations ?',
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'expanded' => true,
                'multiple' => false
            ])
            
            ->add('autre', TextareaType::class, [
                'label' => 'Autre remarques',
                'required'   => false,
            ])
            
            ->add('save', SubmitType::class, ['label' => 'Envoyer le formulaire', 'attr' => ['class' => 'btn-success']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'visiteur_class' => Visiteur::class,
        ]);
    }
}