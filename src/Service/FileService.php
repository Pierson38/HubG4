<?php 

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class FileService {

    private $fileSystem;

    public function __construct(private KernelInterface $appKernel, private EntityManagerInterface $manager)
    {
        $this->fileSystem = new Filesystem();
    }

    public function addImageMessage(UploadedFile $file) : string {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        $file->move(
            $this->appKernel->getProjectDir() . '/public/uploads/messageImages',
            $fileName
        );
        return $fileName;
        
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
    
}