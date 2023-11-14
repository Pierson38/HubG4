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
            $conversations[] = [$value, $messagesRepository->getNotReadCount($user, $value), $messagesRepository->getLastMessage($value)];
        }

        
        return $this->render('messagerie/index.html.twig', [
            'controller_name' => 'MessagerieController',
        ]);
    }

    #[Security("is_granted('IS_AUTHENTICATED_FULLY')")]
    #[Route('/messagerie/{id}', name: 'app_messengers_solo')]
    public function messagesSolo(Conversations $conv, ConversationsRepository $conversationsRepository, MessagesRepository $messagesRepository): Response
    {

        $user = $this->getUserFromInterface();

        $messagesRepository->setNotReadRead($user, $conv);

        $conversations = [];
        $conversationss = $conversationsRepository->getAllUserConversations($user);
        foreach ($conversationss as $value) {
            $conversations[] = [$value, $messagesRepository->getNotReadCount($user, $value), $messagesRepository->getLastMessage($value)];
        }

        
        return $this->render('pages/messengers/messagesSolo.html.twig', [
            'user' => $user,
            'conv' => $conv,
            'conversations' => $conversations
        ]);
    }

    #[Route('/conversation-utils/getTemplateSelfMessage', name: 'app_messengers_self_template', methods: ['POST'])]
    public function getTemplateSelfMessage(Request $request): JsonResponse
    {

        $response = new JsonResponse(
            $this->renderView('components/messengers/messenger_message.html.twig', [
                'picture' => '/images/profile-img.png',
                'message' => $request->request->get('message'),
                'from_current_user' => filter_var($request->request->get('self'), FILTER_VALIDATE_BOOLEAN)
            ])

        );

        return $response;
    }

    #[Security("is_granted('IS_AUTHENTICATED_FULLY')")]
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



        $jsonMessage = $serializer->serialize($message, 'json', [
            'groups' => ['message'] // On serialize la réponse avant de la renvoyer
        ]);


        $update = new Update(
            'https://example.com/conv/' . $conversation->getId(),
            $jsonMessage
        );

        $hub->publish($update);

        $response = new JsonResponse( // Enfin, on retourne la réponse
            'OK',
            Response::HTTP_OK,
            [],
            true
        );
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
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
