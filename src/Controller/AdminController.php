<?php

namespace App\Controller;

use App\Entity\Lbc;
use App\Entity\User;
use App\Enum\LbcCategoriesType;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\ConversationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{

    public function __construct(private UserRepository $userRepository)
    {
    }

    private function getUserFromInterface()
    {
        return $this->userRepository->findOneBy(["email" => $this->getUser()->getUserIdentifier()]);
    }


    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/admin/users', name: 'app_admin_users')]
    public function adminUsers(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        return $this->render('admin/users.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/admin/users/create', name: 'app_admin_users_create')]
    public function adminUsersCreate(EntityManagerInterface $manager, Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {

        $form = $this->createForm(UserType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();
            $user->setPassword($userPasswordHasher->hashPassword($user, $user->getLastname()));

            $role = $form->get("roles")->getData();
            if ($role != "ROLE_USER") {
                $user->setRoles(["ROLE_USER", $role]);
            }
            $user->setPicture("https://picsum.photos/200/300");

            $manager->persist($user);
            $manager->flush();

            $this->addFlash("success", "L'utilisateur a bien été créé");
            return $this->redirectToRoute("app_admin_users");
        }

        return $this->render('admin/usersCreate.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/users/edit/{id}', name: 'app_admin_users_edit')]
    public function adminUsersEdit(User $user, EntityManagerInterface $manager, Request $request): Response
    {

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();
            $manager->persist($user);
            $manager->flush();

            $this->addFlash("success", "L'utilisateur a bien été modifié");
            return $this->redirectToRoute("app_admin_users");
        }

        return $this->render('admin/usersEdit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/users/delete/{id}', name: 'app_admin_users_delete')]
    public function adminUsersDelete(User $user, EntityManagerInterface $manager): Response
    {
        if ($user == $this->getUserFromInterface()) {
            $this->addFlash("error", "Vous ne pouvez pas supprimer votre compte");
            return $this->redirectToRoute("app_admin_users");
        }

        $manager->remove($user);
        $manager->flush();
        $this->addFlash("success", "L'utilisateur a bien été supprimé");
        return $this->redirectToRoute("app_admin_users");
    }
}
