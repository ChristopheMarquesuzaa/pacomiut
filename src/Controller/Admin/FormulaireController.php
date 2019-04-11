<?php

namespace App\Controller\Admin;

use App\Entity\Formulaire;
use App\Entity\Comptage\Porte;
use App\Entity\TempFormulaire;
use App\Entity\Comptage\Visiteur;
use App\Entity\Comptage\Formation;
use App\Entity\Satisfaction\Block;
use App\Entity\Satifiscation\Reponse;
use App\Entity\Satisfaction\Question;
use App\Form\Admin\FormulaireEditType;
use App\Repository\FormulaireRepository;
use App\Form\Admin\FormulaireComptageType;
use App\Form\Satisfaction\BlockQuestionType;
use App\Repository\TempFormulaireRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Form\Admin\FormulaireEditComptageType;
use App\Form\Admin\FormulaireSatisfactionType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\ArrayCollection;
use App\Form\Admin\FormulaireEditSatisfactionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FormulaireController extends AbstractController
{
    /**
     * @Route("/admin/formulaires", name="formulaire")
     * @IsGranted("ROLE_PROF")
     */
    public function index(FormulaireRepository $fp, TempFormulaireRepository $tfp)
    {
        $formulaires = new ArrayCollection(
            array_merge($fp->findAll(), $tfp->findAll())
        );

        return $this->render('/admin/formulaire/index.html.twig', [
            'formulaires' => $formulaires,
        ]);
    }

    /**
     * @Route("/admin/formulaires/create/comptage", name="formulaire_create_comptage")
     * @IsGranted("ROLE_ADMIN")
     */
    public function createComptage(Request $request, ObjectManager $manager)
    {
        $formulaire = new Formulaire();

        $form = $this->createForm(FormulaireComptageType::class, $formulaire);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $formulaire->setType(Formulaire::TYPE_COMPTAGE);
            /*
             * Attention ! Lors d'un ajout / ou edit d'un formulaire,
             * il est important de n'avoir qu'un seul formulaire actif
             * par type et par formation.
             */

            if ($formulaire->getActif()) {
                $formulaire_actif = $manager->getRepository(Formulaire::class)
                    ->findActifByDepartementAndType($formulaire->getDepartement(), $formulaire->getType());
                if ($formulaire_actif) {
                    $formulaire_actif->setActif(false);
                    $manager->persist($formulaire_actif);
                }
            }
            $formulaire->setCreatedAt(new \DateTime());
            $manager->persist($formulaire);

            foreach ($formulaire->getPortes() as $porte) {
                $porte->setFormulaire($formulaire);
            }

            foreach ($formulaire->getFormations() as $formation) {
                $formation->setFormulaire($formulaire);
            }
            $manager->flush();

            $this->addFlash(
                'success',
                'Le formulaire a bien été ajouté.'
            );

            return $this->redirectToRoute('formulaire');
        }

        return $this->render('/admin/formulaire/createComptage.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/comptage/duplicate/{formulaire}", name="formulaire_duplicate_comptage")
     * @IsGranted("ROLE_ADMIN")
     */
    public function duplicateComptage(Formulaire $formulaire, ObjectManager $manager)
    {
        $dFormulaire = new Formulaire();
        $dFormulaire->setName($formulaire->getName() . ' - Duplicata');
        $dFormulaire->setType(Formulaire::TYPE_COMPTAGE);
        $dFormulaire->setDepartement($formulaire->getDepartement());
        $dFormulaire->setActif(false);
        $dFormulaire->setAccompagnateur($formulaire->getAccompagnateur());
        $dFormulaire->setCreatedAt(new \DateTime());

        foreach ($formulaire->getPortes() as $porte) {
            $dPorte = new Porte();
            $dPorte->setName($porte->getName());
            $dPorte->setFormulaire($dFormulaire);
            $manager->persist($dPorte);
        }

        foreach ($formulaire->getFormations() as $formation) {
            $dFormation = new Formation();
            $dFormation->setName($formation->getName());
            $dFormation->setFormulaire($dFormulaire);
            $manager->persist($dFormation);
        }
        $manager->persist($dFormulaire);

        $manager->flush();

        $this->addFlash(
            'success',
            'Le formulaire a bien été dupliqué.'
        );

        return $this->redirectToRoute('formulaire');
    }

        /**
     * @Route("/admin/enquete/duplicate/{formulaire}", name="formulaire_duplicate_enquete")
     * @IsGranted("ROLE_ADMIN")
     */
    public function duplicateEnquete(Formulaire $formulaire, ObjectManager $manager)
    {
        $eFormulaire = new Formulaire();
        $eFormulaire->setName($formulaire->getName() . ' - Duplicata');
        $eFormulaire->setType(Formulaire::TYPE_SATISFACTION);
        $eFormulaire->setDepartement($formulaire->getDepartement());
        $eFormulaire->setActif(false);
        $eFormulaire->setCreatedAt(new \DateTime());

        foreach ($formulaire->getBlocks() as $block) {
            $nblock = new Block();
            $nblock->setName($block->getName());
            $nblock->setFormulaire($eFormulaire);

            foreach ($block->getQuestions() as $question) {
                $nquestion = new Question();
                $nquestion->setTitle($question->getTitle());
                $nquestion->setType($question->getType());
                $nquestion->setBlock($question->getBlock());
                $nquestion->setAnswer($question->getAnswer() ?? "");
                $nblock->addQuestion($nquestion);
                $manager->persist($nquestion);
            }
            $manager->persist($nblock);
        }


        $manager->persist($eFormulaire);

        $manager->flush();

        $this->addFlash(
            'success',
            'Le formulaire a bien été dupliqué.'
        );

        return $this->redirectToRoute('formulaire');
    }

    /**
     * @Route("/admin/formulaires/create/satisfaction", name="formulaire_create_satisfaction")
     * @IsGranted("ROLE_ADMIN")
     */
    public function createSatisfaction(Request $request, ObjectManager $manager)
    {
        $tempForm = new TempFormulaire();

        $form = $this->createForm(FormulaireSatisfactionType::class, $tempForm);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $tempForm->setCreatedAt(new \DateTime());
            foreach ($form->get('blocks')->getData() as $block) {
                $block->setTempFormulaire($tempForm);
                $manager->persist($block);
            }
            $manager->persist($tempForm);
            $manager->flush();

            return $this->redirectToRoute('formulaire_end', [
                'tempForm' => $tempForm->getId(),
            ]);
        }

        return $this->render('/admin/formulaire/createSatisfaction.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/formulaires/create/satisfaction/end/{tempForm}/{blockNumber}", name="formulaire_end")
     * @IsGranted("ROLE_ADMIN")
     */
    public function endFormulaire(TempFormulaire $tempForm, Request $request, ObjectManager $manager, $blockNumber = 0)
    {
        $block = $tempForm->getBlocks()[$blockNumber];
        $form = $this->createForm(BlockQuestionType::class, $block);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($block->getQuestions() as $question) {
                $question->setBlock($block);
            }
            $manager->persist($block);
            $manager->flush();
            $nbrBlock = count($tempForm->getBlocks());
            if ($tempForm->getBlocks()[($nbrBlock - 1)]->getId() == $block->getId()) {
                $formulaire = new Formulaire();
                $formulaire->setName($tempForm->getName());
                $formulaire->setDepartement($tempForm->getDepartement());
                $formulaire->setType(Formulaire::TYPE_SATISFACTION);
                $formulaire->setCreatedAt(new \DateTime());
                if ($tempForm->getActif()) {
                    $formulaire_actif = $manager->getRepository(Formulaire::class)
                        ->findActifByDepartementAndType($formulaire->getDepartement(), $formulaire->getType());
                    if ($formulaire_actif) {
                        $formulaire_actif->setActif(false);
                        $manager->persist($formulaire_actif);
                    }
                }
                $formulaire->setActif($tempForm->getActif());

                foreach ($tempForm->getBlocks() as $block) {
                    $formulaire->addBlock($block);
                    $block->setTempFormulaire(null);
                }
                $manager->persist($formulaire);
                $manager->remove($tempForm);
                $manager->flush();

                $this->addFlash(
                    'success',
                    'Le formulaire a bien été ajouté.'
                );

                return $this->redirectToRoute('formulaire');
            }

            return $this->redirectToRoute('formulaire_end', [
                'tempForm' => $tempForm->getId(),
                'blockNumber' => ($blockNumber + 1),
            ]);
        }

        return $this->render('/admin/formulaire/createQuestion.html.twig', [
            'tempForm' => $tempForm,
            'form' => $form->createView(),
            'block' => $block,
            'blockNumber' => $blockNumber,
            'blockPrecedent' => ($blockNumber - 1),
        ]);
    }

    /**
     * @Route("/admin/formulaires/{formulaire}/edit", name="formulaire_edit")
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Formulaire $formulaire, Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(FormulaireEditType::class, $formulaire);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($formulaire);
            $manager->flush();

            $this->addFlash(
                'success',
                'Le formulaire a bien été édité.'
            );

            return $this->redirectToRoute('formulaire_show', [
                'formulaire' => $formulaire->getId(),
            ]);
        }

        return $this->render('/admin/formulaire/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/admin/formulaires/delete/{formulaire}", name="formulaire_delete")
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Formulaire $formulaire, ObjectManager $manager)
    {
        // Désolé si un jour vous lisez ça; mais plus le temps de rélfléchir :'(), contactez Camille Bourdet.
        $em = $this->getDoctrine()->getManager();
        $sql = "DELETE FROM `formation` WHERE formulaire_id = " . $formulaire->getId(). ";";
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $sql = "DELETE FROM `porte` WHERE formulaire_id = " . $formulaire->getId(). ";";
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $sql = "DELETE FROM `block` WHERE formulaire_id = " . $formulaire->getId(). ";";
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $sql = "DELETE FROM `reponse` WHERE formulaire_id = " . $formulaire->getId(). ";";
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $sql = "DELETE FROM `visiteur` WHERE formulaire_id = " . $formulaire->getId(). ";";
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();

        $sql = "DELETE FROM `formulaire` WHERE id = " . $formulaire->getId(). ";";
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();


        $this->addFlash(
            'success',
            'Le formulaire a bien été supprimé.'
        );

        return $this->redirectToRoute('formulaire');
    }

    /**
     * @Route("/admin/formulaires/comptage/{formulaire}/edit", name="formulaire_edit_comptage")
     * @IsGranted("ROLE_ADMIN")
     */
    public function editComptage(Formulaire $formulaire, Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(FormulaireEditComptageType::class, $formulaire);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($formulaire->getVisiteurs() as $visiteur) {
                $manager->remove($visiteur);
            }
            $manager->persist($formulaire);
            $manager->flush();

            $this->addFlash(
                'success',
                'Le formulaire a bien été édité.'
            );

            return $this->redirectToRoute('formulaire_show', [
                'formulaire' => $formulaire->getId(),
            ]);
        }

        return $this->render('/admin/formulaire/editComptage.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/formulaires/satisfaction/{formulaire}/edit", name="formulaire_edit_satisfaction")
     * @IsGranted("ROLE_ADMIN")œ
     */
    public function editSatisfaction(Formulaire $formulaire, Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(FormulaireEditSatisfactionType::class, $formulaire);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($formulaire->getReponses() as $reponse) {
                $em = $this->getDoctrine()->getManager();
                $sql = "DELETE FROM `result` WHERE reponse_id = " . $reponse->getId(). ";";
                $stmt = $em->getConnection()->prepare($sql);
                $stmt->execute();
            }
            $em = $this->getDoctrine()->getManager();
            $sql = "DELETE FROM `reponse` WHERE formulaire_id = " . $formulaire->getId(). ";";
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->execute();
            $manager->persist($formulaire);
            $manager->flush();

            $this->addFlash(
                'success',
                'Le formulaire a bien été édité.'
            );

            return $this->redirectToRoute('formulaire_edit_block', [
                'formulaire' => $formulaire->getId(),
            ]);
        }

        return $this->render('/admin/formulaire/editSatisfaction.html.twig', [
            'form' => $form->createView(),
        ]);
    }

/**
     * @Route("/admin/formulaires/create/satisfaction/edit/block/{formulaire}/{blockNumber}", name="formulaire_edit_block")
     * @IsGranted("ROLE_ADMIN")
     */
    public function editBlock(Formulaire $formulaire, Request $request, ObjectManager $manager, $blockNumber = 0)
    {
        $block = $formulaire->getBlocks()[$blockNumber];
        $form = $this->createForm(BlockQuestionType::class, $block);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($block->getQuestions() as $question) {
                $question->setBlock($block);
            }
            $manager->persist($block);
            $manager->flush();
            $nbrBlock = count($formulaire->getBlocks());
            if ($formulaire->getBlocks()[($nbrBlock - 1)]->getId() == $block->getId()) {
                foreach ($formulaire->getBlocks() as $block) {
                    $formulaire->addBlock($block);
                }
                $manager->persist($formulaire);
                $manager->flush();

                $this->addFlash(
                    'success',
                    'Le formulaire a bien été édité.'
                );

                return $this->redirectToRoute('formulaire');
            }

            return $this->redirectToRoute('formulaire_edit_block', [
                'formulaire' => $formulaire->getId(),
                'blockNumber' => ($blockNumber + 1),
            ]);
        }

        return $this->render('/admin/formulaire/editQuestion.html.twig', [
            'formulaire' => $formulaire,
            'form' => $form->createView(),
            'block' => $block,
            'blockNumber' => $blockNumber,
            'blockPrecedent' => ($blockNumber - 1),
        ]);
    }

    /**
     * @Route("/admin/formulaires/deleteTemp", name="formulaire_delete_temp")
     * @IsGranted("ROLE_ADMIN")
     */
    public function deleteAllTemp(ObjectManager $manager)
    {
        $tempForms = $manager->getRepository(TempFormulaire::class)->findAll();
        foreach ($tempForms as $tempForm) {
            foreach ($tempForm->getBlocks() as $block) {
                foreach ($block->getQuestions() as $question) {
                    $manager->remove($question);
                }
                $manager->remove($block);
            }
            $manager->remove($tempForm);
        }
        $manager->flush();

        $this->addFlash(
            'success',
            'Les formulaires temporaires ont bien été supprimé.'
        );

        return $this->redirectToRoute('formulaire');
    }

    /**
     * @Route("/admin/formulaires/{formulaire}", name="formulaire_show")
     */
    public function show(Formulaire $formulaire, ObjectManager $manager)
    {
        if ($formulaire->getType() == Formulaire::TYPE_COMPTAGE) {
            return $this->showComptage($formulaire, $manager);
        }

        return $this->showSatisfaction($formulaire, $manager);
    }

    private function showSatisfaction(Formulaire $formulaire, ObjectManager $manager)
    {
        return $this->render('/admin/formulaire/showSatisfaction.html.twig', [
            'formulaire' => $formulaire,
            'nbr' => $manager->getRepository(Reponse::class)->countReponse($formulaire),
            'questions' => $formulaire->getQuestions()
        ]);
    }

    private function showComptage(Formulaire $formulaire, ObjectManager $manager)
    {
        $visiteurs = ($manager->getRepository(Visiteur::class)->findBy(['formulaire' => $formulaire]));

        $cFormation = $formulaire->getFormations();
        foreach ($cFormation as $formation) {
            $formations[] = $formation->getName();
        }

        $cPortes = $formulaire->getPortes();
        foreach ($cPortes as $porte) {
            $portes[] = $porte->getName();
        }

        $accompagnateurByFormation = [];
        foreach ($visiteurs as $visiteur) {
            foreach ($visiteur->getFormations() as $formation) {
                $visiteurByFormation[] = $formation->getName();
                $accompagnateurByFormation = array_merge($accompagnateurByFormation, array_fill(0, $visiteur->getAccompagnateur(), $formation->getName()));
            }
            $visiteurByPorte[] = $visiteur->getPorte()->getName();
        }
        if (isset($visiteurByFormation)) {
            $visiteurByFormation = array_count_values($visiteurByFormation);
            $accompagnateurByFormation = array_count_values($accompagnateurByFormation);
            $visiteurByPorte = array_count_values($visiteurByPorte);
            return $this->render('/admin/formulaire/show.html.twig', [
                'formulaire' => $formulaire,
                'portes' => $formulaire->getPortes(),
                'portes_encode' => json_encode($portes),
                'nombre_porte_encode' => json_encode(array_values($visiteurByPorte)),
                'formations_encode' => json_encode($formations),
                'nombre_formation_encode' => json_encode(array_values($visiteurByFormation)),
                'formations' => $formulaire->getFormations(),
                'nombre_etudiant' => $manager->getRepository(Visiteur::class)->countEtudiant($formulaire),
                'nombre_accompagnateur' => $manager->getRepository(Visiteur::class)->countAccompagnateur($formulaire),
                'nombre_par_porte' => $manager->getRepository(Visiteur::class)->countByPorte($formulaire),
                'tabCountAllFormations' => $visiteurByFormation,
                'tabAccomp' => $accompagnateurByFormation,
            ]);
        }

        return $this->render('/admin/formulaire/show.html.twig', [
            'formulaire' => $formulaire,
            'portes' => $formulaire->getPortes(),
            'portes_encode' => json_encode($portes),
            'formations' => $formulaire->getFormations(),
            'nombre_etudiant' => $manager->getRepository(Visiteur::class)->countEtudiant($formulaire),
            'nombre_accompagnateur' => $manager->getRepository(Visiteur::class)->countAccompagnateur($formulaire),
            'nombre_par_porte' => $manager->getRepository(Visiteur::class)->countByPorte($formulaire),
        ]);
    }

    /**
     * @Route("/admin/tempformulaires/{formulaire}/delete", name="formulaire_temp_delete")
     * @IsGranted("ROLE_ADMIN")
     */
    public function deleteTemp(TempFormulaire $formulaire, ObjectManager $manager)
    {
        $manager->remove($formulaire);
        $manager->flush();

        $this->addFlash(
            'success',
            'Le formulaire a bien été supprimé.'
        );

        return $this->redirectToRoute('formulaire');
    }

    /**
     * @Route("/admin/formulaires/{formulaire}/conclusion", name="conclusion")
     * @IsGranted("ROLE_ADMIN")
     */
    public function storeConclusion(Formulaire $formulaire)
    {
        return $this->redirectToRoute('formulaire_show', [
            'formulaire' => $formulaire->getId(),
        ]);
    }

    /**
     * @Route("/admin/formulaires/{formulaire}/actif", name="formulaire_actif")
     * @IsGranted("ROLE_ADMIN")
     */
    public function actif(Formulaire $formulaire, ObjectManager $manager)
    {
        $formulaire_actif = $manager->getRepository(Formulaire::class)
            ->findActifByDepartementAndType($formulaire->getDepartement(), $formulaire->getType());

        if ($formulaire_actif) {
            $formulaire_actif->setActif(false);
            $manager->persist($formulaire_actif);
        }

        $formulaire->setActif(true);
        $manager->persist($formulaire);
        $manager->flush();

        $this->addFlash(
            'success',
            'Le formulaire a bien été passé en actif.'
        );

        return $this->redirectToRoute('formulaire_show', [
            'formulaire' => $formulaire->getId(),
        ]);
    }

    /**
     * @Route("/admin/formulaires/{formulaire}/inactif", name="formulaire_inactif")
     * @IsGranted("ROLE_ADMIN")
     */
    public function inactif(Formulaire $formulaire, ObjectManager $manager)
    {
        $formulaire->setActif(false);
        $manager->persist($formulaire);
        $manager->flush();

        $this->addFlash(
            'success',
            'Le formulaire a bien été rendu inactif.'
        );

        return $this->redirectToRoute('formulaire_show', [
            'formulaire' => $formulaire->getId(),
        ]);
    }
}
