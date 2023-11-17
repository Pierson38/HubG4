<?php

namespace App\Controller;

use App\Entity\Lbc;
use App\Entity\LbcPictures;
use App\Form\LbcType;
use App\Repository\LbcRepository;
use App\Service\FileService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LBCController extends AbstractController
{
    #[Route('/lbc', name: 'app_lbc')]
    public function index(LbcRepository $lbcRepository): Response
    {
        return $this->render('lbc/index.html.twig', [
            'products' => $lbcRepository->findAll()
        ]);
    }

    #[Route('/lbc/create', name: 'app_lbc_create')]
    public function new(Request $request, EntityManagerInterface $entityManager, FileService $fileService): Response
    {
        // just set up a fresh $task object (remove the example data)
        $lbc = new Lbc();

        $form = $this->createForm(LbcType::class, $lbc);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $lbc = $form->getData();
            $lbc->setCreatedBy($this->getUser());
            $entityManager->persist($lbc);
            $entityManager->flush();
            // dd($lbc);
            $pictures = $request->files->all()['lbc']['images'];
            if ($pictures) {
                $fileService->addImagesToLbc($lbc, $pictures);
            }
            $this->addFlash("success", "Votre annonce a bien été posté");
            return $this->redirectToRoute('app_lbc');
        }

        return $this->render('lbc/createLbc.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/lbc/edit/{id}', name: 'app_lbc_edit')]
    public function edit(Lbc $lbc, Request $request, FileService $fileService, EntityManagerInterface $entityManager): Response
    {

        $form = $this->createForm(LbcType::class, $lbc);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $lbc = $form->getData();
            $lbc->setUpdatedAt(new DateTimeImmutable());
            // dd($lbc);

            $entityManager->persist($lbc);
            $entityManager->flush();

            $pictures = $request->files->all()['lbc']['images'];
            if ($pictures) {
                $fileService->addImagesToLbc($lbc, $pictures);
            }
            $this->addFlash("success", "Votre annonce a bien été modifié");
            return $this->redirectToRoute('app_lbc');
        }

        return $this->render('lbc/editLbc.html.twig', [
            'form' => $form,
            'lbc' => $lbc,
        ]);
    }

    #[Route('/lbc/delete-image/{id}', name: 'app_lbc_delete_image')]
    public function deleteImage(LbcPictures $lbcPictures, Request $request, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($lbcPictures);
        $entityManager->flush();

        $this->addFlash("success", "Votre image a bien été supprimé");
        return $this->redirectToRoute("app_lbc_edit", ['id' => $lbcPictures->getLbc()->getId()]);
    }

    #[Route('/lbc/delete/{id}', name: 'app_lbc_delete')]
    public function delete(Lbc $lbc, Request $request, EntityManagerInterface $entityManager): Response
    {

        $entityManager->remove($lbc);
        $entityManager->flush();

        $this->addFlash("success", "Votre annonce a bien été supprimé");

        return $this->redirectToRoute("app_lbc");
    }


    #[Route('/lbc/detail/{id}', name: "app_lbc_detail")] public function showProductDetail(Lbc  $product): Response
    {

        return $this->render('lbc/detail.html.twig', [
            'product' => $product,
        ]);
    }
}
