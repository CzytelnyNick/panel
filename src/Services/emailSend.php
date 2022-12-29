<?php

namespace App\Services\emailSend;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;

class EmailSend
{
    private EmailVerifier $emailVerifier;
    private UserRepository $userRepository;
    public function __construct(EmailVerifier $emailVerifier, UserRepository $userRepository)
    {
        $this->emailVerifier = $emailVerifier;
        $this->userRepository = $userRepository;
    }

    public function send(string $text)
    {
        $user = $this->userRepository->findAll();
        foreach ($user as $el) {
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $el,
                (new TemplatedEmail())
                    ->from(new Address('testowedamian1@gmail.com', 'Testowy Mail Bot'))
                    ->to($el->getEmail())
                    ->subject('Mamy nową wiadomość dla ciebie')
                    ->textTemplate($text)
//                ->htmlTemplate('registration/confirmation_email.html.twig')
            );

        }


//        return $this->render('admin/show.html.twig', [
//            'users' => $users
//        ]);
    }
}