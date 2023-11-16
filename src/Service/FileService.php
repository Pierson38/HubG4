<?php

namespace App\Service;

use App\Entity\Files;
use App\Entity\Folder;
use App\Entity\Permissions;
use App\Entity\Promo;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\KernelInterface;


class FileService
{

    private $fileSystem;

    public function __construct(private KernelInterface $appKernel, private EntityManagerInterface $manager)
    {
        $this->fileSystem = new Filesystem();
    }

    private function formatSizeUnits($bytes) {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' Go';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' Mo';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' Ko';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' octets';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' octet';
        } else {
            $bytes = '0 octet';
        }
    
        return $bytes;
    }

    public function changeUserImageProfile(User $user, UploadedFile $image): string
    {

        if ($user->getPicture() != null) {
            $path = $this->appKernel->getProjectDir() . '/public' . $user->getPicture();
            try {
                $this->fileSystem->remove($path);
            } catch (\Throwable $th) {
                throw $th;
            }
        }

        $fileName = md5(uniqid()) . '.' . $image->guessExtension();
        $image->move(
            $this->appKernel->getProjectDir() . '/public/uploads/userImages',
            $fileName
        );
        $user->setPicture('/uploads/userImages/' . $fileName);
        $this->manager->persist($user);
        $this->manager->flush();
        return $fileName;
    }

    public function addImageMessage(UploadedFile $file): string
    {
        $fileName = md5(uniqid()) . '.' . $file->guessExtension();
        $file->move(
            $this->appKernel->getProjectDir() . '/public/uploads/messageImages',
            $fileName
        );
        return $fileName;
    }
    
    public function getWeightOfFilesFolder(Folder $folder, $totalSize = 0)
    {

        $path = $folder->getPath();
        $files = scandir($path);

        foreach ($files as $file) {
            if (is_file($path . '/' . $file)) {
                $totalSize += filesize($path . '/' . $file);
            }
        }

        if ($folder->getChildren()) {
            foreach ($folder->getChildren()->getValues() as $folderChild) {
                $totalSize += $this->getWeightOfFilesFolder($folderChild, $totalSize);
            }
        } 

        return $totalSize;
    }

    public function getNumberOfFiles(Folder $folder, $count = 0)
    {
        $count += $folder->getFiles() ? count($folder->getFiles()->getValues()) : 0;

        if ($folder->getChildren()) {
            foreach ($folder->getChildren()->getValues() as $folderChild) {
                $count += $this->getWeightOfFilesFolder($folderChild, $count);
            }
        }

        return $count;
    }

    public function createPersonalRepository(User $user)
    {

        $folder = new Folder();
        $folder->setName("Dossier personnel " . $user->getFirstName() . " " . $user->getLastName());
        $folder->setCreatedBy($user);
        $folder->setParent(null);
        $this->manager->persist($folder);

        $permissions = new Permissions();
        $permissions->setUser($user);
        $permissions->setFolder($folder);
        $permissions->setIsReadable(true);
        $permissions->setIsEditable(true);
        $permissions->setIsDeletable(false);
        $this->manager->persist($permissions);

        $this->manager->flush();

        $path = $this->appKernel->getProjectDir() . '/public/uploads/drive/personals/' . $folder->getId();
        $folder->setPath($path);

        $this->manager->persist($folder);
        $this->manager->flush();

        if (!$this->fileSystem->exists($path)) {
            $this->fileSystem->mkdir($path, 0777, true);
        }
    }

    public function removePersonalRepository(User $user)
    {
        $path = $this->appKernel->getProjectDir() . '/public/uploads/drive/personals/' . $user->getId();
        try {
            $this->fileSystem->remove($path);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function createPromoRepository(Promo $promo)
    {

        $folder = new Folder();
        $folder->setName($promo->getName());
        $folder->setCreatedBy(null);
        $folder->setParent(null);
        $this->manager->persist($folder);

        $permissions = new Permissions();
        $permissions->setPromo($promo);
        $permissions->setFolder($folder);
        $permissions->setIsReadable(true);
        $permissions->setIsEditable(true);
        $permissions->setIsDeletable(false);
        $this->manager->persist($permissions);

        $this->manager->flush();

        $path = $this->appKernel->getProjectDir() . '/public/uploads/drive/promos/' . $folder->getId();
        $folder->setPath($path);

        $this->manager->persist($folder);
        $this->manager->flush();
        if (!$this->fileSystem->exists($path)) {
            $this->fileSystem->mkdir($path, 0777, true);
        }

        return $folder;
    }

    public function removePromoRepository(Promo $promo)
    {
        $path = $this->appKernel->getProjectDir() . '/public/uploads/drive/promos/' . $promo->getId();
        try {
            $this->fileSystem->remove($path);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function createDirectory(Folder $parentFolder, string $name, User $user, array $permissionsData)
    {
        $folder = new Folder();
        $folder->setName($name);
        $folder->setCreatedBy($user);
        $folder->setParent($parentFolder);
        $this->manager->persist($folder);

        $permissions = new Permissions();
        if ($parentFolder->getPermissions()[0]->getPromo() != null) {
            $permissions->setPromo($parentFolder->getPermissions()[0]->getPromo());
        } else {
            $permissions->setUser($parentFolder->getPermissions()[0]->getUser());
        }
        $permissions->setFolder($folder);
        $permissions->setIsReadable($permissionsData['isReadable']);
        $permissions->setIsEditable($permissionsData['isEditable']);
        $permissions->setIsDeletable($permissionsData['isDeletable']);
        $this->manager->persist($permissions);

        $this->manager->flush();

        $path = $parentFolder->getPath() . '/' . $folder->getId();
        $folder->setPath($path);

        $this->manager->persist($folder);
        $this->manager->flush();

        if (!$this->fileSystem->exists($path)) {
            $this->fileSystem->mkdir($path, 0777, true);
        }

        return $folder;
    }

    public function removeDirectory(Folder $folder)
    {
        $path = $folder->getPath();
        try {
            $this->fileSystem->remove($path);
        } catch (\Throwable $th) {
            throw $th;
        }

        $this->manager->remove($folder);
        $this->manager->flush();
    }

    public function addFileInDirectory(Folder $folder, UploadedFile $uploadedFile, User $user)
    {

        $file = new Files();
        $file->setName($uploadedFile->getClientOriginalName());
        $file->setWeight($this->formatSizeUnits($uploadedFile->getSize()));
        $file->setExtension($uploadedFile->guessExtension());
        $file->setFolder($folder);
        $file->setCreatedBy($user);

        $this->manager->persist($file);

        $folder->setUpdatedAt(new \DateTimeImmutable());
        $this->manager->persist($folder);
        $this->manager->flush();

        $uploadedFile->move(
            $folder->getPath(),
            $file->getId() . '.' . $uploadedFile->guessExtension()
        );
        return $file;
    }

    public function removeFile(Files $file)
    {
        $path = $file->getFolder()->getPath() . '/' . $file->getId() . '.' . $file->getExtension();
        try {
            $this->fileSystem->remove($path);
        } catch (\Throwable $th) {
            throw $th;
        }

        $this->manager->remove($file);
        $this->manager->flush();
    }
}
