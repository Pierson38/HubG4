<?php 

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\KernelInterface;

class FileService {

    public function __construct(private KernelInterface $appKernel)
    {
        
    }

    public function addImageMessage(UploadedFile $file) : string {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        $file->move(
            $this->appKernel->getProjectDir() . '/public/uploads/messageImages',
            $fileName
        );
        return $fileName;
        
    }
}