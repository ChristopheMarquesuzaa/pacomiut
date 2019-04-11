<?php

namespace App\Controller\Comptage;

use App\Entity\Comptage\Visiteur;
use App\Entity\Departement;
use App\Entity\Formulaire;
use App\Form\Comptage\VisiteurType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ComptageController extends AbstractController
{
    /**
     * @Route("/{departement}/comptage", name="comptage")
     */
    public function index(Departement $departement, ObjectManager $manager, Request $request)
    {
        // On vérifie si il existe déjà un formulaire de comptage actif pour cette formation
        $formulaire = $manager->getRepository(Formulaire::class)
            ->findActifByDepartementAndType($departement, Formulaire::TYPE_COMPTAGE);

        if ($formulaire) {
            $visiteur = new Visiteur();
            $visiteur->setFormulaire($formulaire);
            $form = $this->createForm(VisiteurType::class, $visiteur);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $visiteur->setCreatedAt(new \DateTime());
                $manager->persist($visiteur);
                $manager->flush();

                $this->addFlash(
                    'success',
                    'Le formulaire a bien été ajouté.'
                );

                return $this->redirectToRoute('comptage', ['departement' => $formulaire->getDepartement()->getId()]);
            }

            return $this->render('comptage/index.html.twig', [
                'form' => $form->createView(),
                'formulaire' => $formulaire,
            ]);
        } else {
            // Dans le cas ou aucun formulaire actif n'a été défini
            return $this->render('comptage/no_index.html.twig');
        }
    }
}
