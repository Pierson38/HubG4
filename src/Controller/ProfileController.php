<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\FileService;
use App\Form\PasswordResetType;
use App\Form\ProfilePictureType;
use App\Repository\UserRepository;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ProfileController extends AbstractController
{

    function __construct(
        private UserPasswordHasherInterface $userPasswordHasher,
        private UserRepository $userRepository,
        private FileService $fileService
    ) {
    }

    
    private function getUserFromInterface()
    {
        return $this->userRepository->findOneBy(["email" => $this->getUser()->getUserIdentifier()]);
    }


    #[Route('/profile', name: 'app_profile')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {

        // we retrieve the user connected
        $user = $this->getUserFromInterface();

        $profilePictureForm = $this->createForm(ProfilePictureType::class, $user);
        $profilePictureForm->handleRequest($request);

        if ($profilePictureForm->isSubmitted() && $profilePictureForm->isValid()) {
            $profilePicture = $request->files->all()['profile_picture']['picture'];

            if ($profilePicture) {
                // Call the changeUserImageProfile method from the FileService
                $this->fileService->changeUserImageProfile($user, $profilePicture);

                $this->addFlash('success', 'Votre photo de profile a bien été modifiée');
            }
        }

        // // Separate forms
        $passwordResetForm = $this->createForm(PasswordResetType::class , $user);
        $passwordResetForm->handleRequest($request);

        if ($passwordResetForm->isSubmitted() && $passwordResetForm->isValid()) {
            $newPassword = $passwordResetForm->get('Password')['first']->getData();
            $repeatPassword = $passwordResetForm->get('Password')['second']->getData();

            // Check if the passwords match
            if ($newPassword === $repeatPassword) {

                // Your custom logic for hashing the password
                $hashedPassword = $this->userPasswordHasher->hashPassword($user, $newPassword);
                $user->setPassword($hashedPassword);

                // Persist and flush the changes to the database
                $em->persist($user);
                $em->flush();


                $this->addFlash('success', 'Votre mot de passe a bien été modifié');
                return $this->redirectToRoute('app_profile');
            } else {
                $this->addFlash('error', 'Les mots de passe ne correspondent pas');
                // You might want to handle the error and redirect back to the password reset form
                return $this->redirectToRoute('app_profile');
            }
        }

        return $this->render('profile.html.twig', [
            'controller_name' => 'ProfileController',
            'profilePictureForm' => $profilePictureForm->createView(),
            'passwordResetForm' => $passwordResetForm->createView(),
        ]);
    }
}
