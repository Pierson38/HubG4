<?php

namespace App\Controller;

use App\Form\PasswordResetType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class ProfileController extends AbstractController
{

    function __construct(private UserPasswordHasherInterface $userPasswordHasher) {

    }


    #[Route('/profile', name: 'app_profile')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(PasswordResetType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle password reset logic here
            $newPassword = $form->get('plainPassword')->getData();
            // Update the user's password using the UserPasswordHasherInterface
            // $user = $this->getUser();
            // $hashedPassword = $this->userPasswordHasher->hashPassword($user, $newPassword);
            // $user->setPassword($hashedPassword);
            // $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Password updated successfully');

            // Redirect or do whatever you need after password reset
        }

        return $this->render('edit-profile.html.twig', [
            'controller_name' => 'ProfileController',
            'resetForm' => $form->createView(),
        ]);
    }
}
