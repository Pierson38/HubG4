<?php

namespace App\Controller;

use App\Entity\Carpool;
use App\Entity\CarpoolMembers;
use App\Form\CarpoolType;
use App\Repository\CarpoolRepository;
use App\Repository\UserRepository;
use App\Service\EmailService;
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
    public function join(Carpool $carpool, EmailService $emailService, EntityManagerInterface $manager): Response
    {
        
        // dd($carpool->getCreatedBy() == $this->getUserFromInterface(), $carpool->getCarpoolMembers()->count() >= $carpool->getPlaces(), $this->checkIfUserIsInCarpool($carpool), $carpool->getPlaces(), $carpool->getCarpoolMembers()->count());
        if ($carpool->getCreatedBy() == $this->getUserFromInterface() || $carpool->getMembersCount() >= $carpool->getPlaces() || $this->checkIfUserIsInCarpool($carpool)) {
            $this->addFlash('error', 'Vous ne pouvez pas rejoindre le covoiturage.');
            return $this->redirectToRoute('app_covoit_solo', ['id' => $carpool->getId()]);
        }


        $member = new CarpoolMembers();
        $member->setCarpool($carpool);
        $member->setUser($this->getUserFromInterface());
        $member->setIsAccepted(false);
        $manager->persist($member);
        $manager->flush();
        $emailService->sendEmail($carpool->getCreatedBy(), 'carpool_request', [
            'link' => $this->generateUrl('app_covoit_solo', ['id' => $carpool->getId()]),
            "user" => $this->getUserFromInterface(),
        ]);

        $this->addFlash('success', 'Votre demande a bien été envoyée.');
        return $this->redirectToRoute('app_covoit_solo', ['id' => $carpool->getId()]);
    }

    #[Route('/carpool/accept/{id}', name: 'app_covoit_accept')]
    public function accept(CarpoolMembers $member, EmailService $emailService, EntityManagerInterface $manager): Response
    {
        $carpool = $member->getCarpool();
        if ($carpool->getMembersCount() >= $carpool->getPlaces()) {
            $this->addFlash('error', "Il n'y a plus de place de disponible");
            return $this->redirectToRoute('app_covoit_solo', ['id' => $carpool->getId()]);
        }
        
        $member->setIsAccepted(true);
        $manager->persist($member);
        $manager->flush();

        $emailService->sendEmail($this->getUserFromInterface(), "carpool_accept", [
            "link" => $this->generateUrl("app_covoit_solo", ["id" => $member->getCarpool()->getId()]),
            "carpool" => $member->getCarpool(),
            "isAccepted" => true,
        ]);

        $this->addFlash('success', 'Vous avez accepté la demande de covoiturage.');
        return $this->redirectToRoute('app_covoit_solo', ['id' => $member->getCarpool()->getId()]);
    }

    #[Route('/carpool/decline/{id}', name: 'app_covoit_decline')]
    public function decline(CarpoolMembers $member, EmailService $emailService, EntityManagerInterface $manager): Response
    {
        
        $manager->remove($member);
        $manager->flush();

        $emailService->sendEmail($this->getUserFromInterface(), "carpool_refuse", [
            "link" => $this->generateUrl("app_covoit"),
            "carpool" => $member->getCarpool(),
            "isAccepted" => false,
        ]);

        $this->addFlash('success', 'Vous avez refusé la demande de covoiturage.');
        return $this->redirectToRoute('app_covoit_solo', ['id' => $member->getCarpool()->getId()]);
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
    public function delete(Carpool $carpool, EntityManagerInterface $manager, EmailService $emailService): Response
    {

        $manager->remove($carpool);
        $manager->flush();
        $this->addFlash('success', 'Votre covoiturage a bien été supprimé.');

        foreach ($carpool->getCarpoolMembers() as $member) {
            if ($member->isIsAccepted()) {
                $emailService->sendEmail($member->getUser(), "carpool_cancel", [
                    "link" => $this->generateUrl("app_covoit"),
                    "carpool" => $member->getCarpool(),
                ]);
            }
        }

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
