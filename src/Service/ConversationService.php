<?php 

namespace App\Service;

use App\Entity\Conversations;
use App\Entity\User;
use App\Repository\ConversationsRepository;
use Doctrine\ORM\EntityManagerInterface;

class ConversationService {

    public function __construct(private ConversationsRepository $conversationsRepository, private EntityManagerInterface $manager)
    {
        
    }

    public function createConversation(User $user, User $contact) : Conversations {

        if ($conversation = $this->conversationsRepository->verifConversation($contact, $user)) {
            return $conversation;
        } else {
            $conversation = new Conversations();
            $conversation->setFromUser($user);
            $conversation->setToUser($contact);

            $this->manager->persist($conversation);
            $this->manager->flush();

            return $conversation;
        }
        
    }
}