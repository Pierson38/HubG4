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

    public function getWeightOfFilesFolder(Folder $folder) {


        $path = $folder->getPath();
        $totalSize = 0;
        $files = scandir($path);

        foreach ($files as $file) {
            if (is_file($path . '/' . $file)) {
                $totalSize += filesize($path . '/' . $file);
            }
        }

        return $this->formatSizeUnits($totalSize);
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

        $this->manager->flush();

        $path = $this->appKernel->getProjectDir() . '/public/uploads/drive/personals/' . $folder->getId();
        $folder->setPath($path);

        $this->manager->persist($folder);
        $this->manager->flush();

        if (!$this->fileSystem->exists($path)) {
            $this->fileSystem->mkdir($path, 0777, true);
        }

       
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

        $this->manager->flush();
        
        $path = $this->appKernel->getProjectDir() . '/public/uploads/drive/promos/' . $folder->getId();
        $folder->setPath($path);

        $this->manager->persist($folder);
        $this->manager->flush();
        if (!$this->fileSystem->exists($path)) {
            $this->fileSystem->mkdir($path, 0777, true);
        }
        

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