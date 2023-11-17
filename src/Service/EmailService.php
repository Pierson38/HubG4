<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class EmailService
{

    public function __construct(private MailerInterface $mailer)
    {
    }

    private function getSubject($type)
    {
        switch ($type) {
            case 'new_message':
                return 'Vous avez reçu un nouveau message';
            case "new_event":
                    return 'Nouvel évènement';
            case 'registration':
                return 'Bienvenue sur HubG4';
            case 'carpool_request':
                return 'Demande de covoiturage';
            case 'carpool_accept':
                return 'Demande de covoiturage acceptée';
            case 'carpool_refuse':
                return 'Demande de covoiturage refusée';
            case 'carpool_cancel':
                return 'Covoiturage annulée';
            case 'new_course':
                return 'Nouveau cours';
            default:
                return 'HubG4';
        }
    }

    private function getTemplate($type)
    {
        $toReturn = null;
        switch ($type) {
            case 'new_message':
                $toReturn = 'emails/new-message.html.twig';
                break;
            case "new_event":
                $toReturn = 'emails/new-event.html.twig';
                break;
            case 'registration':
                $toReturn = 'emails/new-account.html.twig';
                break;
            case 'carpool_request':
                $toReturn = 'emails/covoit-someone-wants-to-join.html.twig';
                break;
            case 'carpool_accept':
                $toReturn = 'emails/covoit-request-answer.html.twig';
                break;
            case 'carpool_refuse':
                $toReturn = 'emails/covoit-request-answer.html.twig';
                break;
            case 'carpool_cancel':
                $toReturn = 'emails/covoit-cancel.html.twig';
                break;
            case 'new_course':
                $toReturn = 'emails/new-course.html.twig';
                break;
            default:
                $toReturn = null;
        }

        return $toReturn;
    }

    public function sendEmail($user, $type, $data = [])
    {
        $email = (new TemplatedEmail())
            ->from(new Address('xfranc225@gmail.com', 'HubG4'))
            ->to($user->getEmail())
            ->subject($this->getSubject($type))
            ->htmlTemplate($this->getTemplate($type))
            ->context([
                'data' => $data,
            ]);

        $this->mailer->send($email);
    }
}
