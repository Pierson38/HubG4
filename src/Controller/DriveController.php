<?php

namespace App\Controller;

use App\Entity\Files;
use App\Entity\Folder;
use App\Repository\FolderRepository;
use App\Repository\UserRepository;
use App\Service\FileService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
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

        $folders = $this->isGranted("ROLE_PROFESSOR") ? $folderRepository->getAllBaseUserFolders() : $folderRepository->getBaseUserFolders($user, $promo);
        // $folders = $folderRepository->getBaseUserFolders($user, $promo);


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

    private function checkPermissions(Folder $folder, string $type)
    {
        $permissions = $folder->getPermissions()[0];
        $user = $this->getUserFromInterface();
        $return = false;

        if ($this->isGranted('ROLE_PROFESSOR')) {
            return false;
        }
        switch ($type) {
            case 'delete':
                if ($permissions->getUser() != null) {
                    if ($permissions->getUser() != $user || !$permissions->isIsDeletable()) {
                        $return = true;
                    }
                } else {
                    if ($permissions->getPromo() != $user->getPromo() || !$permissions->isIsDeletable()) {
                        $return = true;
                    }
                }
                break;
            case 'edit':
                if ($permissions->getUser() != null) {
                    if ($permissions->getUser() != $user || !$permissions->isIsEditable()) {
                        $return = true;
                    }
                } else {
                    if ($permissions->getPromo() != $user->getPromo() || !$permissions->isIsEditable()) {
                        $return = true;
                    }
                }
                break;
            case 'read':
                if ($permissions->getUser() != null) {
                    if ($permissions->getUser() != $user || !$permissions->isIsReadable()) {
                        $return = true;
                    }
                } else {
                    if ($permissions->getPromo() != $user->getPromo() || !$permissions->isIsReadable()) {
                        $return = true;
                    }
                }
                    break;
            default:
                # code...
                break;
        }

        return $return;
    }

    #[Route('/drive/create-directory/{id}', name: 'app_drive_create_directory')]
    public function createDirectory(Folder $parentFolder, Request $request, FileService $fileService): JsonResponse
    {
        $user = $this->getUserFromInterface();

        if ($this->checkPermissions($parentFolder, 'edit')) {
            $this->addFlash('error', 'Vous n\'avez pas les droits pour supprimer ce dossier');
            return new JsonResponse("Ok");
        }

        $name = $request->request->get('name');
        $isEditable = $request->request->get('isEditable');
        $isDeletable = $request->request->get('isDeletable');
        $isReadable = $request->request->get('isReadable');
        $permissions = [
            'isEditable' => $isEditable,
            'isDeletable' => $isDeletable,
            'isReadable' => $isReadable
        ];

        $fileService->createDirectory($parentFolder, $name, $user, $permissions);

        $this->addFlash('success', 'Le dossier a bien été créé');

        return new JsonResponse($parentFolder->getId());
    }

    #[Route('/drive/rename-directory/{id}', name: 'app_drive_rename_directory')]
    public function renameDirectory(Folder $folder, EntityManagerInterface $manager, Request $request): JsonResponse
    {

        if ($this->checkPermissions($folder, 'edit')) {
            $this->addFlash('error', 'Vous n\'avez pas les droits pour renomer ce dossier');
            return new JsonResponse("Ok");
        }

        $name = $request->request->get('name');
        $folder->setName($name);
        $folder->setUpdatedAt(new DateTimeImmutable());
        $manager->persist($folder);
        $manager->flush();


        $this->addFlash('success', 'Le dossier a bien été renomé');

        return new JsonResponse("Ok");
    }

    #[Route('/drive/get-directory-permissions/{id}', name: 'app_drive_get_permissions_directory', methods: ['GET'])]
    public function getPermissionsDirectory(Folder $folder): JsonResponse
    {

        if ($this->checkPermissions($folder, 'edit')) {
            $this->addFlash('error', 'Vous n\'avez pas les droits pour récupérer les permissions de ce dossier');
            return new JsonResponse("Ok");
        }

        $permissions = $folder->getPermissions()[0];

        $data = ["isEditable" => $permissions->isIsEditable(), "isDeletable" => $permissions->isIsDeletable(), "isReadable" => $permissions->isIsReadable()];

        return new JsonResponse($data);
    }

    #[Route('/drive/edit-directory-permissions/{id}', name: 'app_drive_edit_permissions_directory', methods: ['POST'])]
    public function changePermissionsDirectory(Folder $folder, EntityManagerInterface $manager, Request $request): JsonResponse
    {

        if ($this->checkPermissions($folder, 'edit')) {
            $this->addFlash('error', 'Vous n\'avez pas les droits pour changer les permissions de ce dossier');
            return new JsonResponse("Ok");
        }

        $permission = $folder->getPermissions()[0];
        $isEditable = $request->request->get('isEditable');
        $isDeletable = $request->request->get('isDeletable');
        $isReadable = $request->request->get('isReadable');
        // dd($isDeletable, $isEditable, $isReadable);

        $permission->setIsEditable(filter_var($isEditable, FILTER_VALIDATE_BOOLEAN));
        $permission->setIsDeletable(filter_var($isDeletable, FILTER_VALIDATE_BOOLEAN));
        $permission->setIsReadable(filter_var($isReadable, FILTER_VALIDATE_BOOLEAN));
        $manager->persist($permission);
        $manager->flush();

        $this->addFlash('success', 'Les permissions ont bien été modifiées');

        return new JsonResponse("Ok");
    }


    #[Route('/drive/delete-directory/{id}', name: 'app_drive_delete_directory')]
    public function deleteDirectory(Folder $folder, FileService $fileService): JsonResponse
    {

        if ($this->checkPermissions($folder, 'delete')) {
            $this->addFlash('error', 'Vous n\'avez pas les droits pour supprimer ce dossier');
            return new JsonResponse("Ok");
        }

        $fileService->removeDirectory($folder);

        $this->addFlash('success', 'Le dossier a bien été supprimé');

        return new JsonResponse("Ok");
    }

    #[Route('/drive/add-file/{id}', name: 'app_drive_add_file')]
    public function addFile(Folder $folder, Request $request, FileService $fileService): JsonResponse
    {
        if ($this->checkPermissions($folder, 'edit')) {
            $this->addFlash('error', 'Vous n\'avez pas les droits pour supprimer ce dossier');
            return new JsonResponse("Ok");
        }

        if (empty($folder)) {
            throw new AccessDeniedHttpException('Vous devez être dans un dossier');
        }
        $user = $this->getUserFromInterface();
        // dd($folder, $request->files->get('file'), $user);
        $file = $fileService->addFileInDirectory($folder, $request->files->get('file'), $user);

        $this->addFlash('success', 'Le fichier a bien été uploadé');


        return new JsonResponse($file->getId());
    }

    #[Route('/drive/delete-file/{id}', name: 'app_drive_delete_file')]
    public function deleteFile(Files $file, FileService $fileService): JsonResponse
    {

        if ($this->checkPermissions($file->getFolder(), 'delete')) {
            $this->addFlash('error', 'Vous n\'avez pas les droits pour supprimer ce fichier');
            return new JsonResponse("Ok");
        }

        $fileService->removeFile($file);

        $this->addFlash('success', 'Le fichier a bien été supprimé');

        return new JsonResponse("Ok");
    }

    #[Route('/drive/download-file/{id}', name: 'app_drive_download_file')]
    public function downloadFile(Files $file): Response
    {

        if ($this->checkPermissions($file->getFolder(), 'read')) {
            $this->addFlash('error', 'Vous n\'avez pas les droits pour télécharger ce fichier');
            return new JsonResponse("Ok");
        }

        return $this->file($file->getFolder()->getPath() . '/' . $file->getId() . '.' . $file->getExtension(), $file->getName());
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
