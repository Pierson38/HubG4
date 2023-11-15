<?php

namespace App\Controller;

use App\Entity\Courses;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function index(): Response
    {
        $user = $this->getUserFromInterface();

        $courses = $user->getPromo()->getCourses()->getValues();

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
}
