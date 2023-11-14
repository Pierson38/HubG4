<?php

namespace App\Controller;

use App\Repository\ConversationsRepository;
use App\Repository\MessagesRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessagerieController extends AbstractController
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    private function getUserFromInterface()
    {
        return $this->userRepository->findOneBy(["email" => $this->getUser()->getUserIdentifier()]);
    }

    #[Route('/messagerie', name: 'app_messagerie')]
    public function index(ConversationsRepository $conversationsRepository, MessagesRepository $messagesRepository): Response
    {
        $user = $this->getUserFromInterface();

        $conversations = [];
        $conversationss = $conversationsRepository->getAllUserConversations($user);
        foreach ($conversationss as $value) {
            $conversations[] = [$value, $messagesRepository->getNotReadCount($user, $value), $messagesRepository->getLastMessage($value)];
        }

        
        return $this->render('messagerie/index.html.twig', [
            'controller_name' => 'MessagerieController',
        ]);
    }
}
