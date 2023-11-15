<?php

namespace App\Controller;

use App\Repository\LbcRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LBCController extends AbstractController
{
    #[Route('/lbc', name: 'app_lbc')]
    public function index(LbcRepository $lbcRepository): Response
    {
       // dd($lbcRepository->findAll());

     /*   $lbc = new Lbc();
       $lbc->setCategory("Immobilier");
       $lbc->getCategory(); */

       //product.category
        return $this->render('lbc/index.html.twig', [
            'products' => $lbcRepository->findAll()
        ]);
    }
}
