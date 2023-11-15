<?php

namespace App\EventListener;

use App\Entity\Folder;
use App\Service\FileService;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class FolderEventListener
{
    public function __construct(private FileService $fileService)
    {
    }

    public function postLoad(LifecycleEventArgs $args) {
        $entity = $args->getObject();
        if ($entity instanceof Folder) {
            $entity->setFilesCount(count($entity->getFiles()->getValues()));
            $entity->setWeight($this->fileService->getWeightOfFilesFolder($entity));
        }
    }
}