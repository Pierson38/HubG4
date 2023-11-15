<?php 

namespace App\Service;

use App\Entity\Folder;
use App\Entity\Permissions;
use App\Entity\Promo;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\KernelInterface;

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

    public function createPersonalRepository(User $user) {

        $folder = new Folder();
        $folder->setName("Personal");
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

        $path = $this->appKernel->getProjectDir() . '/public/uploads/drive/personals/' . $user->getId();
        if (!$this->fileSystem->exists($path)) {
            $this->fileSystem->mkdir($path, 0777, true);
        }

        $this->manager->flush();
    }

    public function removePersonalRepository(User $user) {
        $path = $this->appKernel->getProjectDir() . '/public/uploads/drive/personals/' . $user->getId();
        try {
            $this->fileSystem->remove($path);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function createPromoRepository(Promo $promo) {

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

        
        $path = $this->appKernel->getProjectDir() . '/public/uploads/drive/promos/' . $promo->getId();
        if (!$this->fileSystem->exists($path)) {
            $this->fileSystem->mkdir($path, 0777, true);
        }
        
        $this->manager->flush();

    }

    

    public function removePromoRepository(Promo $promo) {
        $path = $this->appKernel->getProjectDir() . '/public/uploads/drive/promos/' . $promo->getId();
        try {
            $this->fileSystem->remove($path);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}