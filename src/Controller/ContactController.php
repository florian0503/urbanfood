<?php

namespace App\Controller;

use App\Entity\ContactMessage;
use App\Form\ContactMessageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

final class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer,
        #[Autowire(env: 'CONTACT_EMAIL_TO')] string $contactEmailTo,
        #[Autowire(env: 'CONTACT_EMAIL_FROM')] string $contactEmailFrom,
    ): Response {
        $contactMessage = new ContactMessage();
        $form = $this->createForm(ContactMessageType::class, $contactMessage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Honeypot rempli = bot : on simule le succes sans rien enregistrer.
            if ('' === (string) $form->get('website')->getData()) {
                $entityManager->persist($contactMessage);
                $entityManager->flush();

                $this->sendNotification($mailer, $contactMessage, $contactEmailTo, $contactEmailFrom);
            }

            $this->addFlash('contact_sent', true);

            return $this->redirectToRoute('app_contact');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form,
        ]);
    }

    private function sendNotification(
        MailerInterface $mailer,
        ContactMessage $contactMessage,
        string $contactEmailTo,
        string $contactEmailFrom,
    ): void {
        $email = (new Email())
            ->from(new Address($contactEmailFrom, "Urban's Food — Site web"))
            ->to($contactEmailTo)
            ->replyTo(new Address((string) $contactMessage->getEmail(), (string) $contactMessage->getName()))
            ->subject(sprintf('Nouveau message : %s', $contactMessage->getSubject()))
            ->text(sprintf(
                "Nouveau message recu via le formulaire du site.\n\nNom : %s\nEmail : %s\nSujet : %s\n\nMessage :\n%s\n",
                $contactMessage->getName(),
                $contactMessage->getEmail(),
                $contactMessage->getSubject(),
                $contactMessage->getMessage(),
            ));

        try {
            $mailer->send($email);
        } catch (TransportExceptionInterface) {
            // L'envoi echoue silencieusement : le message reste consultable
            // dans l'admin, on ne casse pas le parcours utilisateur.
        }
    }
}
