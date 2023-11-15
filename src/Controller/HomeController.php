<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;

class HomeController extends AbstractController
{
    public function __construct(Environment $twig)
    {
        $this->loader = $twig->getLoader();
    }

    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->redirectToRoute('app_courses');
    }

    // #[Route('/{path}')]
    // public function root($path)
    // {
    //     if ($this->loader->exists($path.'.html.twig')) {
    //         if ($path == '/' || $path == 'home') {
    //             die('Home');
    //         }
    //         return $this->render($path.'.html.twig');
    //     }
    //     throw $this->createNotFoundException('The Requested Page Not Found.');
    // }
}
