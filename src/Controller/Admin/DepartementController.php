<?php

namespace App\Controller\Admin;

use App\Entity\Departement;
use App\Form\Admin\DepartementType;
use App\Repository\DepartementRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DepartementController extends AbstractController
{
    /**
     * @Route("/admin/departements", name="departement", methods="GET")
     */
    public function index(DepartementRepository $dp): Response
    {
        return $this->render('/admin/departement/index.html.twig', [
            'departements' => $dp->findAll(),
        ]);
    }

    /**
     * @Route("/admin/departements/create", name="departement_create", methods="GET|POST")
     */
    public function create(Request $request, ObjectManager $manager): Response
    {
        $departement = new Departement();

        $form = $this->createForm(DepartementType::class, $departement);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($departement);
            $manager->flush();

            $this->addFlash(
                'success',
                'Le département a bien été ajouté.'
            );

            return $this->redirectToRoute('departement');
        }

        return $this->render('/admin/departement/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/departements/{departement}", name="departement_show", methods="GET")
     */
    public function show(Departement $departement): Response
    {
        return $this->render('/admin/departement/show.html.twig', [
            'departement' => $departement,
        ]);
    }

    /**
     * @Route("/admin/departements/{departement}/edit", name="departement_edit", methods="GET|POST")
     */
    public function edit(Departement $departement, Request $request, ObjectManager $manager): Response
    {
        $form = $this->createForm(DepartementType::class, $departement);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($departement);
            $manager->flush();

            $this->addFlash(
                'success',
                'Le département a bien été ajouté.'
            );

            return $this->redirectToRoute('departement');
        }

        return $this->render('/admin/departement/edit.html.twig', [
            'form' => $form->createView(),
            'departement' => $departement,
        ]);
    }

    /**
     * @Route("/admin/departements/{departement}/delete", name="departement_delete")
     */
    public function delete(Departement $departement, ObjectManager $manager): Response
    {
        $manager->remove($departement);
        $manager->flush();

        $this->addFlash(
            'success',
            'Le département a bien été supprimé.'
        );

        return $this->redirectToRoute('departement');
    }
}
