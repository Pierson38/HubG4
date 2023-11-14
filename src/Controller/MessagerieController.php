<?php

namespace App\Controller;

use App\Entity\Conversations;
use App\Entity\Messages;
use App\Entity\User;
use App\Repository\ConversationsRepository;
use App\Repository\MessagesRepository;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

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
            if ($value->getFromUser() == $user) {
                $otherUser = $value->getToUser();
            } else {
                $otherUser = $value->getFromUser();
            }
            $conversations[] = [$value, $messagesRepository->getNotReadCount($user, $value), $messagesRepository->getLastMessage($value), $otherUser];
        }

        if (count($conversations) > 0) {
            $conv = $conversations[0][0];
            $messagesRepository->setNotReadRead($user, $conv);
        } else {
            $conv = null;
        }
       
        // dd($conversations[0]);

        
        return $this->render('messagerie/chat.html.twig', [
            'user' => $user,
            'conv' => $conv,
            'conversations' => $conversations
        ]);
    }

    // #[Security("is_granted('IS_AUTHENTICATED_FULLY')")]
    #[Route('/messagerie/{id}', name: 'app_messengers_solo')]
    public function messagesSolo(Conversations $conv, ConversationsRepository $conversationsRepository, MessagesRepository $messagesRepository): Response
    {

        $user = $this->getUserFromInterface();

        $messagesRepository->setNotReadRead($user, $conv);

        $conversations = [];
        $conversationss = $conversationsRepository->getAllUserConversations($user);
        foreach ($conversationss as $value) {
            if ($value->getFromUser() == $user) {
                $otherUser = $value->getToUser();
            } else {
                $otherUser = $value->getFromUser();
            }
            $conversations[] = [$value, $messagesRepository->getNotReadCount($user, $value), $messagesRepository->getLastMessage($value), $otherUser];
        }

        
        return $this->render('messagerie/chat.html.twig', [
            'user' => $user,
            'conv' => $conv,
            'conversations' => $conversations
        ]);
    }

    #[Route('/delete-conversation/{id}', name: 'app_messagerie_delete')]
    public function deleteConv(Conversations $conversation, EntityManagerInterface $manager): Response
    {

    
        $manager->remove($conversation);
        $manager->flush();

        $this->addFlash('success', "La conversation a bien été supprimée");

        
        return $this->redirectToRoute('app_messagerie');
    }

    #[Route('/conversation-utils/getTemplateSelfMessage/{id}', name: 'app_messengers_self_template', methods: ['GET'])]
    public function getTemplateSelfMessage(Messages $message, Request $request): JsonResponse
    {

        $response = new JsonResponse(
            $this->renderView('messagerie/message.html.twig', [
                'message' => $message,
            ])

        );

        return $response;
    }

    // #[Security("is_granted('IS_AUTHENTICATED_FULLY')")]
    #[Route('/conversation/{id}/add-message', name: 'app_messengers_add_message', methods: ['POST'])]
    public function sendMessage(Conversations $conversation = null, Request $request, SerializerInterface $serializer, EntityManagerInterface $manager, HubInterface $hub, UserRepository $userRepository): JsonResponse
    {
        if (empty($content = $request->request->get('content'))) {
            throw new AccessDeniedHttpException('No data sent');
        }

        if (empty($conversation)) {
            throw new AccessDeniedHttpException('Message have to be sent on a specific conversation');
        }

        $message = new Messages();
        $message->setContent($content);
        $message->setConversation($conversation);
        $message->setCreatedBy($userRepository->findOneBy(['id' => (int)$request->request->get('userId')]));

        $conversation->setUpdatedAt(new DateTimeImmutable());
        $manager->persist($conversation);
        $manager->persist($message);
        $manager->flush();


        $response = new JsonResponse( // Enfin, on retourne la réponse
            $message->getId(),
            Response::HTTP_OK,
            [],
            true
        );
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }

    // #[Security("is_granted('IS_AUTHENTICATED_FULLY')")]
    #[Route('/conversation/{id}/get-messages', name: 'app_messengers_get_message', methods: ['GET'])]
    public function getMessages(Conversations $conversation, Request $request, MessagesRepository $messagesRepository, SerializerInterface $serializer, EntityManagerInterface $manager): JsonResponse
    {
        $messages = $messagesRepository->getLastMessages($conversation, $this->getUserFromInterface());
        $toReturn = [];
        foreach ($messages as $value) {
            $toReturn[] = $serializer->serialize($value, 'json', [
                'groups' => ['message'] // On serialize la réponse avant de la renvoyer
            ]);

            $value->setIsRead(true);
            $manager->persist($value);
        }
        $manager->flush();
        return new JsonResponse($toReturn);
    }



    #[Security("is_granted('IS_AUTHENTICATED_FULLY')")]
    #[Route('/create-conversation/{id}', name: 'app_messengers_create_converstation_user')]
    public function createConversationUser(User $user, EntityManagerInterface $manager, ConversationsRepository $conversationsRepository): Response
    {


        if ($user == $this->getUser()) {
            $this->addFlash('error', "Vous ne pouvez pas créer de discution avec vous même");
            return $this->redirectToRoute('app_homepage');
        }
        if ($conversation = $conversationsRepository->verifConversation($user, $this->getUserFromInterface())) {
            return $this->redirectToRoute('app_messengers_solo', ['id' => $conversation->getId()]);
        } else {
            $conversation = new Conversations();
            $conversation->setFromUser($user);
            $conversation->setToUser($this->getUser());

            $manager->persist($conversation);
            $manager->flush();

            return $this->redirectToRoute('app_messengers_solo', ['id' => $conversation->getId()]);
        }
    }
}
