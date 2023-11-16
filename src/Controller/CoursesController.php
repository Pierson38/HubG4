<?php

namespace App\Controller;

use App\Entity\Courses;
use App\Form\CourseProfessorType;
use App\Form\CoursesType;
use App\Repository\CoursesRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CoursesController extends AbstractController
{

    public function __construct(private UserRepository $userRepository)
    {
    }

    private function getUserFromInterface()
    {
        return $this->userRepository->findOneBy(["email" => $this->getUser()->getUserIdentifier()]);
    }

    #[Route('/courses', name: 'app_courses')]
    public function index(CoursesRepository $coursesRepository): Response
    {
        $user = $this->getUserFromInterface();

        if ($this->isGranted("ROLE_COP")) {
            $courses = $coursesRepository->findAll();
        } elseif ($this->isGranted("ROLE_PROFESSOR")) {
            $courses = $user->getCoursesProfessor()->getValues();
        } else {
            $courses = $user->getPromo()->getCourses()->getValues();
        }

        return $this->render('courses/index.html.twig', [
            'courses' => $courses,
        ]);
    }

    #[Route('/course/{id}', name: 'app_courses_single')]
    public function singleCourse(Courses $course): Response
    {
        return $this->render('courses/course.html.twig', [
            'course' => $course,
        ]);
    }

    #[Route('/courses/create', name: 'app_courses_create')]
    public function adminCoursesCreate(EntityManagerInterface $manager, Request $request): Response
    {
        $form = $this->createForm(CourseProfessorType::class);
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
            return $this->redirectToRoute("app_courses");
        }

        return $this->render('courses/coursesCreate.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/courses/edit/{id}', name: 'app_courses_edit')]
    public function adminCoursesEdit(Courses $course, EntityManagerInterface $manager, Request $request): Response
    {
        $tags = $course->getTags();
        $newTags = [];
        foreach ($tags as $value) {
            $newTags[] = ["text" => $value];
        }
        $course->setTags($newTags);

        $form = $this->createForm(CourseProfessorType::class, $course);
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
            return $this->redirectToRoute("app_courses");
        }

        return $this->render('courses/coursesEdit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/courses/delete/{id}', name: 'app_courses_delete')]
    public function adminCoursesDelete(Courses $courses, EntityManagerInterface $manager): Response
    {
        $manager->remove($courses);
        $manager->flush();
        $this->addFlash("success", "Le cours a bien été supprimé");
        return $this->redirectToRoute("app_courses_courses");
    }
}
