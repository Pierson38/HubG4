<?php

namespace App\Controller;

use App\Entity\Courses;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CourseController extends AbstractController
{
    #[Route('/course', name: 'course_list')]
    public function index(): Response
    {
        return $this->render('course/list.html.twig', [
            'controller_name' => 'CourseController',
        ]);
    }

    #[Route('/course/{id}', name: 'course_detail')]
    public function detail(Courses $course): Response
    {
        // dd($course);
        return $this->render('course/detail.html.twig', [
            'course' => $course,
        ]);
    }
}
