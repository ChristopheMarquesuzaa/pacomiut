<?php

namespace App\Controller\Satisfaction;

use App\Entity\Formulaire;
use App\Entity\Departement;
use App\Entity\Satisfaction\Result;
use App\Entity\Satifiscation\Reponse;
use App\Entity\Satisfaction\Question;
use App\Form\Satisfaction\ResultType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SatisfactionController extends AbstractController
{
    /**
     * @Route("/{departement}/satisfaction", name="satisfaction")
     */
    public function index(Departement $departement, ObjectManager $manager, Request $request)
    {
        // On vérifie si il existe déjà un formulaire de comptage actif pour cette formation
        $formulaire = $manager->getRepository(Formulaire::class)
            ->findActifByDepartementAndType($departement, Formulaire::TYPE_SATISFACTION);

        if ($formulaire) {
            $response = new Reponse();
            $response->setFormulaire($formulaire);
            $form = $this->createForm(ResultType::class, $response);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                foreach ($request->get('questions') as $k => $q) {
                    $result = new Result();
                    $result->setReponse($response);
                    $question = $manager->getRepository(Question::class)->find($k);
                    $result->setQuestion($question);
                    if ($question->getType() == Question::TYPE_CHECKBOX || $question->getType() == Question::TYPE_SELECT_MULTIPE) {
                        $result->setValue(json_encode($q));
                    } else {
                        $result->setValue($q);
                    }
                    $result->setCreatedAt(new \DateTime());
                    $manager->persist($result);
                }
                $response->setCreatedAt(new \DateTime());
                $manager->persist($response);
                $manager->flush();

                $this->addFlash(
                    'success',
                    'Le formulaire a bien été ajouté.'
                );

                return $this->redirectToRoute('satisfaction', ['departement' => $formulaire->getDepartement()->getId()]);
            }

            return $this->render('satisfaction/index.html.twig', [
                'form' => $form->createView(),
                'formulaire' => $formulaire,
            ]);
        } else {
            // Dans le cas ou aucun formulaire actif n'a été défini
            return $this->render('satisfaction/no_index.html.twig');
        }
    }
}
