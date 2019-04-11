<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\Admin\UserEditType;
use App\Form\Admin\UserPasswordType;
use App\Form\Admin\UserType;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;

/**
 * @Route("/admin/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('admin/user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword((new BCryptPasswordEncoder(15))->encodePassword($form->get('password')->getData(), true));
            switch ($form->get('roles')->getData()) {
                case 'admin':
                    $user->setRoles(['ROLE_ADMIN']);
                    break;
                case 'enseignant':
                    $user->setRoles(['ROLE_PROF']);
                    break;
                default:
                    $user->setRoles(['']);
                    break;
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('admin/user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/reset", name="user_reset")
     */
    public function reset(ObjectManager $manager)
    {
        $connection = $manager->getConnection();
        $platform = $connection->getDatabasePlatform();
        $connection->executeUpdate($platform->getTruncateTableSQL('user', true));
        // Re-création du compte admin
        $admin = new User();
        $admin->setEmail('admin@admin.admin');
        $admin->setUsername('Admin');
        $admin->setFirstname('Admin');
        $admin->setSurname('Admin');
        $admin->setPassword('$2y$15$8KReq0wptYA9OFWdwssbHuwuA2T/8ISBhj0V491/SdMP00mSSvJ8m');
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        $manager->flush();

        return $this->redirectToRoute('admin');
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('admin/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user, ObjectManager $manager): Response
    {
        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            switch ($form->get('roles')->getData()) {
                case 'admin':
                    $user->setRoles(['ROLE_ADMIN']);
                    break;
                case 'enseignant':
                    $user->setRoles(['ROLE_PROF']);
                    break;
                default:
                    $user->setRoles(['']);
                    break;
            }
            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('user_index', [
                'id' => $user->getId(),
            ]);
        }

        return $this->render('admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("{id}/delete", name="user_delete")
     */
    public function delete(User $user, ObjectManager $manager): Response
    {
        if ($user->getId() == 1) {
            $this->addFlash(
                'warning',
                'Le compte numéro 1 ne peut être supprimé.'
            );
        } else {
            $manager->remove($user);
            $manager->flush();

            $this->addFlash(
                'success',
                'L\'utilisateur a bien été supprimé.'
            );
        }

        return $this->redirectToRoute('user_index');
    }

    /**
     * @Route("/{id}/password", name="user_password")
     */
    public function updatePassword(User $user, Request $request, ObjectManager $manager): Response
    {
        $form = $this->createForm(UserPasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword((new BCryptPasswordEncoder(15))->encodePassword($form->get('password')->getData(), true));
            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('user_index', [
                'id' => $user->getId(),
            ]);
        }

        return $this->render('admin/user/updatePassword.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
