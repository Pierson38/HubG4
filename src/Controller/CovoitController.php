<?php

namespace App\Controller;

use App\Entity\Carpool;
use App\Entity\CarpoolMembers;
use App\Form\CarpoolType;
use App\Repository\CarpoolRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CovoitController extends AbstractController
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    private function getUserFromInterface()
    {
        return $this->userRepository->findOneBy(["email" => $this->getUser()->getUserIdentifier()]);
    }

    #[Route('/carpool', name: 'app_covoit')]
    public function index(CarpoolRepository $carpoolRepository): Response
    {
        return $this->render('covoit/index.html.twig', [
            'carpools' => $carpoolRepository->findAll(),
        ]);
    }

    private function checkIfUserIsInCarpool(Carpool $carpool)
    {
        foreach ($carpool->getCarpoolMembers() as $member) {
            if ($member->getUser() == $this->getUserFromInterface()) {
                return true;
            }
        }
        return false;
    }

    #[Route('/carpool/join/{id}', name: 'app_covoit_join')]
    public function join(Carpool $carpool, EntityManagerInterface $manager): Response
    {
        

        if ($carpool->getCreatedBy() == $this->getUserFromInterface() || $carpool->getPlaces() >= $carpool->getCarpoolMembers()->count() || $this->checkIfUserIsInCarpool($carpool)) {
            $this->addFlash('error', 'Vous ne pouvez pas rejoindre le covoiturage.');
            return $this->redirectToRoute('app_covoit_solo', ['id' => $carpool->getId()]);
        }


        $member = new CarpoolMembers();
        $member->setCarpool($carpool);
        $member->setUser($this->getUserFromInterface());
        $member->setIsAccepted(false);
        $manager->persist($member);
        $manager->flush();
        $this->addFlash('success', 'Votre demande a bien été envoyée.');
        return $this->redirectToRoute('app_covoit_solo', ['id' => $carpool->getId()]);
    }

    #[Route('/carpool/create', name: 'app_covoit_create')]
    public function create(Request $request, EntityManagerInterface $manager): Response
    {
        $carpool = new Carpool();

        $form = $this->createForm(CarpoolType::class, $carpool);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $carpool = $form->getData();
            $carpool->setCreatedBy($this->getUserFromInterface());
            $manager->persist($carpool);
            $manager->flush();
            $this->addFlash('success', 'Votre covoiturage a bien été créé.');
            return $this->redirectToRoute('app_covoit_solo', ['id' => $carpool->getId()]);
        }

        return $this->render('covoit/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/carpool/edit/{id}', name: 'app_covoit_edit')]
    public function edit(Carpool $carpool, Request $request, EntityManagerInterface $manager): Response
    {

        $form = $this->createForm(CarpoolType::class, $carpool);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $carpool = $form->getData();
            $manager->persist($carpool);
            $manager->flush();
            $this->addFlash('success', 'Votre covoiturage a bien été modifié.');
            return $this->redirectToRoute('app_covoit_solo', ['id' => $carpool->getId()]);
        }

        return $this->render('covoit/edit.html.twig', [
            'form' => $form->createView(),
            'carpool' => $carpool,
        ]);
    }

    #[Route('/carpool/delete/{id}', name: 'app_covoit_delete')]
    public function delete(Carpool $carpool, EntityManagerInterface $manager): Response
    {

        $manager->remove($carpool);
        $manager->flush();
        $this->addFlash('success', 'Votre covoiturage a bien été supprimé.');

        return $this->redirectToRoute('app_covoit');
    }

    #[Route('/carpool/{id}', name: 'app_covoit_solo')]
    public function solo(Carpool $carpool): Response
    {
        return $this->render('covoit/show.html.twig', [
            'carpool' => $carpool,
        ]);
    }
}
