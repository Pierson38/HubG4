<?php

namespace App\Controller;

use App\Entity\Events;
use App\Form\CreateEventType;
use App\Repository\EventsRepository;
use App\Repository\UserRepository;
use DateInterval;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CalendarController extends AbstractController
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    private function getUserFromInterface()
    {
        return $this->userRepository->findOneBy(["email" => $this->getUser()->getUserIdentifier()]);
    }

    #[Route('/calendar', name: 'app_calendar')]
    public function index(EventsRepository $eventsRepository, EntityManagerInterface $manager, Request $request): Response
    {
        $user = $this->getUserFromInterface();

        $courses = $user->getPromo()->getCourses()->getValues();

        $events = $eventsRepository->findAll();

        $myEvents = $user->getEvents()->getValues();
        $form = $this->createForm(CreateEventType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event = $form->getData();

            $event->setCreatedBy($user);
            $type = "bde";
            if (in_array("ROLE_ADMIN", $user->getRoles())) {
                $type = "admin";
            } elseif (in_array("ROLE_PROFESSOR", $user->getRoles())) {
                $type = "professor";
            } elseif (in_array("ROLE_BDE", $user->getRoles())) {
                $type = "bde";
            }
            $event->setType($type);
            $event->setCreatedAt(new DateTimeImmutable());
            $event->setUpdatedAt(new DateTimeImmutable());

            $manager->persist($event);
            $manager->flush();

            return $this->redirectToRoute('app_calendar');
        }

        return $this->render('calendar/index.html.twig', [
            'controller_name' => 'ClendarController',
            'events' => $events,
            'courses' => $courses,
            'form' => $form->createView(),
            'myEvents' => $myEvents
        ]);
    }

    #[Route('/calendar/get-events/', name: 'app_calendar_getevents', methods: ['GET'])]
    public function requestByDay(EventsRepository $eventsRepository, SerializerInterface $serializer): JsonResponse
    {

        $user = $this->getUserFromInterface();

        $courses = $user->getPromo()->getCourses()->getValues();

        $events = $eventsRepository->findAll();

        $all = [];

        foreach ($courses as $value) {
            $all[] = $serializer->serialize($value, 'json', [
                'groups' => ['coursesEvent'] // On serialize la réponse avant de la renvoyer
            ]);
        }

        foreach ($events as $value) {
            $all[] = $serializer->serialize($value, 'json', [
                'groups' => ['eventsEvent'] // On serialize la réponse avant de la renvoyer
            ]);
        }

        return new JsonResponse($all);
    }

    #[Route('/calendar/delete-event/{id}', name: 'app_calendar_deleteEvent', methods: ['GET', 'POST'])]
    public function deleteEvent(Events $event, EntityManagerInterface $manager): Response
    {
        $manager->remove($event);
        $manager->flush();

        $this->addFlash('success', "L'évènement a bien été supprimé");

        return $this->redirectToRoute('app_calendar');
    }

}
