<?php

namespace App\Controller;

use App\Entity\Courses;
use App\Entity\Promo;
use App\Entity\User;
use App\Form\CoursesType;
use App\Form\PromosType;
use App\Form\UserType;
use App\Repository\CoursesRepository;
use App\Repository\PromoRepository;
use App\Repository\UserRepository;
use App\Service\ConversationService;
use App\Service\FileService;
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
    public function adminUsersCreate(EntityManagerInterface $manager, Request $request, UserPasswordHasherInterface $userPasswordHasher, FileService $fileService): Response
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

            $fileService->createPersonalRepository($user);

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
    public function adminUsersDelete(User $user, EntityManagerInterface $manager, FileService $fileService): Response
    {
        if ($user == $this->getUserFromInterface()) {
            $this->addFlash("error", "Vous ne pouvez pas supprimer votre compte");
            return $this->redirectToRoute("app_admin_users");
        }

        $fileService->removePersonalRepository($user);
        $manager->remove($user);
        $manager->flush();
        $this->addFlash("success", "L'utilisateur a bien été supprimé");
        return $this->redirectToRoute("app_admin_users");
    }

    #[Route('/admin/courses', name: 'app_admin_courses')]
    public function adminCourses(CoursesRepository $coursesRepository, PromoRepository $promoRepository): Response
    {
        $promos = $promoRepository->findAll();
        $courses = $coursesRepository->findAll();
        // dd($courses);
        return $this->render('admin/courses.html.twig', [
            'courses' => $courses,
            'promos' => $promos,
        ]);
    }

    #[Route('/admin/courses/create', name: 'app_admin_courses_create')]
    public function adminCoursesCreate(EntityManagerInterface $manager, Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {

        $form = $this->createForm(CoursesType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $course = $form->getData();
            $tags = $course->getTags();
            $newTags = [];
            foreach ($tags as $value) {
                $newTags[] = $value["text"];
            }
            $course->setTags($newTags);
            $course->setCreatedBy($this->getUserFromInterface());

            $manager->persist($course);
            $manager->flush();

            $this->addFlash("success", "Le cours a bien été créé");
            return $this->redirectToRoute("app_admin_courses");
        }

        return $this->render('admin/coursesCreate.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/courses/edit/{id}', name: 'app_admin_courses_edit')]
    public function adminCoursesEdit(Courses $course, EntityManagerInterface $manager, Request $request): Response
    {
        $tags = $course->getTags();
        $newTags = [];
        foreach ($tags as $value) {
            $newTags[] = ["text" => $value];
        }
        $course->setTags($newTags);

        $form = $this->createForm(CoursesType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $course = $form->getData();
            $tags = $course->getTags();
            $newTags = [];
            foreach ($tags as $value) {
                $newTags[] = $value["text"];
            }
            $course->setTags($newTags);
            $manager->persist($course);
            $manager->flush();

            $this->addFlash("success", "Le cours a bien été modifié");
            return $this->redirectToRoute("app_admin_courses");
        }

        return $this->render('admin/coursesEdit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/courses/delete/{id}', name: 'app_admin_courses_delete')]
    public function adminCoursesDelete(Courses $courses, EntityManagerInterface $manager): Response
    {
        $manager->remove($courses);
        $manager->flush();
        $this->addFlash("success", "Le cours a bien été supprimé");
        return $this->redirectToRoute("app_admin_courses");
    }

    #[Route('/admin/promos', name: 'app_admin_promos')]
    public function adminPromos(PromoRepository $promoRepository): Response
    {
        $promos = $promoRepository->findAll();
        return $this->render('admin/promos.html.twig', [
            'promos' => $promos,
        ]);
    }

    #[Route('/admin/promos/create', name: 'app_admin_promos_create')]
    public function adminPromosCreate(EntityManagerInterface $manager, Request $request, FileService $fileService): Response
    {

        $form = $this->createForm(PromosType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $promo = $form->getData();

            $manager->persist($promo);
            $manager->flush();

            $fileService->createPromoRepository($promo);

            $this->addFlash("success", "La promo a bien été créé");
            return $this->redirectToRoute("app_admin_promos");
        }

        return $this->render('admin/promosCreate.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/promos/edit/{id}', name: 'app_admin_promos_edit')]
    public function adminPromosEdit(Promo $promo, EntityManagerInterface $manager, Request $request): Response
    {
        $form = $this->createForm(PromosType::class, $promo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $promo = $form->getData();
            $manager->persist($promo);
            $manager->flush();

            $this->addFlash("success", "La promo a bien été modifié");
            return $this->redirectToRoute("app_admin_promos");
        }

        return $this->render('admin/promosEdit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/promos/delete/{id}', name: 'app_admin_promos_delete')]
    public function adminPromosDelete(Promo $promo, EntityManagerInterface $manager, FileService $fileService): Response
    {
        $fileService->removePromoRepository($promo);
        $manager->remove($promo);
        $manager->flush();
        $this->addFlash("success", "La promo a bien été supprimé");
        return $this->redirectToRoute("app_admin_promos");
    }
}
