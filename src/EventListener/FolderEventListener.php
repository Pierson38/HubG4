<?php

namespace App\EventListener;

use App\Entity\Carpool;
use App\Entity\Folder;
use App\Repository\CarpoolMembersRepository;
use App\Service\FileService;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class FolderEventListener
{
    public function __construct(private FileService $fileService, private CarpoolMembersRepository $carpoolMembersRepository)
    {
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

    public function postLoad(LifecycleEventArgs $args) {
        $entity = $args->getObject();
        if ($entity instanceof Folder) {
            $entity->setFilesCount($this->fileService->getNumberOfFiles($entity));
            $entity->setWeight($this->formatSizeUnits($this->fileService->getWeightOfFilesFolder($entity)));
        }

        if ($entity instanceof Carpool) {
            $entity->setMembersCount($this->carpoolMembersRepository->getMembersCount($entity));
        }
    }
}