<?php

namespace App\Controller;

use App\Entity\Events;
use App\Form\CreateEventType;
use App\Repository\CarpoolRepository;
use App\Repository\EventsRepository;
use App\Repository\UserRepository;
use App\Service\EmailService;
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
    public function index(EventsRepository $eventsRepository, EmailService $emailService, EntityManagerInterface $manager, Request $request, UserRepository $userRepository): Response
    {
        $user = $this->getUserFromInterface();

        $courses = $user->getPromo()->getCourses()->getValues();

        $events = $eventsRepository->findAll();

        $myEvents = $user->getEvents()->getValues();

        $event = new Events();
        $event->setStartAt(new DateTimeImmutable());
        $event->setEndAt(new DateTimeImmutable());
        $form = $this->createForm(CreateEventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event = $form->getData();

            if (count($form->get('promo')->getData()) > 0) {
                foreach ($form->get('promo')->getData() as $promo) {
                    $event->addPromo($promo);

                    foreach ($promo->getUsers() as $userPromo) {
                        $emailService->sendEmail($userPromo, "new_event", [
                            "link" => $this->generateUrl("app_calendar"),
                        ]);
                    }
                }
            } else {
                foreach ($userRepository->findAll() as $userEvent) {
                    $emailService->sendEmail($userEvent, "new_event", [
                        "link" => $this->generateUrl("app_calendar"),
                    ]);
                }
            }

            $event->setCreatedBy($user);
            $type = "bde";
            if ($this->isGranted("ROLE_COP")) {
                $type = "cop";
            } elseif ($this->isGranted("ROLE_PROFESSOR")) {
                $type = "professor";
            } elseif ($this->isGranted("ROLE_BDE")) {
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
    public function requestByDay(EventsRepository $eventsRepository, SerializerInterface $serializer, CarpoolRepository $carpoolRepository): JsonResponse
    {

        $user = $this->getUserFromInterface();

        $courses = $user->getPromo()->getCourses()->getValues();

        $eventsPromo = $user->getPromo()->getEvents()->getValues();

        $events = $eventsRepository->getEventsForAll();

        $carpools = $carpoolRepository->getCarpoolAccepted($user);

        $all = [];

        foreach ($courses as $value) {
            $all[] = $serializer->serialize($value, 'json', [
                'groups' => ['coursesEvent'] // On serialize la réponse avant de la renvoyer
            ]);
        }

        foreach ($eventsPromo as $value) {
            $all[] = $serializer->serialize($value, 'json', [
                'groups' => ['eventsEvent'] // On serialize la réponse avant de la renvoyer
            ]);
        }

        foreach ($events as $value) {
            $all[] = $serializer->serialize($value, 'json', [
                'groups' => ['eventsEvent'] // On serialize la réponse avant de la renvoyer
            ]);
        }

        foreach ($carpools as $value) {
            $all[] = $serializer->serialize($value, 'json', [
                'groups' => ['carpoolEvent'] // On serialize la réponse avant de la renvoyer
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
