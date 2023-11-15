<?php

namespace App\Controller;

use App\Entity\Folder;
use App\Repository\FolderRepository;
use App\Repository\UserRepository;
use App\Service\FileService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DriveController extends AbstractController
{

    public function __construct(private UserRepository $userRepository)
    {
    }

    private function getUserFromInterface()
    {
        return $this->userRepository->findOneBy(["email" => $this->getUser()->getUserIdentifier()]);
    }

    #[Route('/drive', name: 'app_drive')]
    public function index(FolderRepository $folderRepository): Response
    {
        $user = $this->getUserFromInterface();
        $promo = $user->getPromo();

        $folders = $folderRepository->getBaseUserFolders($user, $promo);
        // dd($folders);


        return $this->render('drive/index.html.twig', [
            'folders' => $folders,
            'hub' => true
        ]);
    }

    #[Route('/drive/{id}', name: 'app_drive_folder')]
    public function folder(Folder $folder): Response
    {
        $user = $this->getUserFromInterface();

        $folders = $folder->getChildren()->getValues();
        // dd($folders);


        return $this->render('drive/index.html.twig', [
            'folderBase' => $folder,
            'folders' => $folders,
            'hub' => false
        ]);
    }
/* 
    #[Route('/create-drive', name: 'app_create_drive')]
    public function createDrive(FileService $fileService): Response
    {
        $user = $this->getUserFromInterface();
        $promo = $user->getPromo();

        $fileService->createPersonalRepository($user);
        // $fileService->createPromoRepository($promo);


        return $this->redirectToRoute('app_drive');
    } */
    
}
