<?php

namespace App\Controller;

use App\Entity\Courses;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
    public function detail(Courses $course, EntityManagerInterface $entityManager): Response
    {
        // Récupérez le titre depuis la base de données (par exemple, le premier enregistrement ici)
        $title = $course->getTitle();
        $description = $course->getDescription();
        $startat = $course->getStartAt();
        $endat = $course->getEndAt();
        $professor = $course->getProfessor();


        // Vérifiez si le titre existe
        if (!$title || !$description || !$startat || !$endat || !$professor) {
            throw $this->createNotFoundException('Aucune donnée trouvée dans la base de données.');
        }
        // dd($course);
        return $this->render('course/detail.html.twig', [
            'course' => $course,
            'title' => $title,
            'description' => $description,
            'startat' => $startat,
            'endat' => $endat,
            'professor' => $professor,
        ]);
    }

}
