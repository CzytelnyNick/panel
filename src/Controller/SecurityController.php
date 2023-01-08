<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;
use function MongoDB\BSON\toJSON;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
    public function __construct(){

    }
    #[Route(path: '/change', name: 'change_password')]
    public function changePassword(UserRepository $user, UserPasswordHasherInterface $passwordHasher, Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository) {
//        dd($this->getUser());
        $id = $this->getUser()->getId();
        $user = $entityManager->getRepository(User::class)->find($id);
//        $user = $this->getUser();
//        dd($user);
//        dd($request);
//
//        $user = $ntityManager->getRepository(User::class)->findBy(['email' => $email]);

        $form = $this->createForm(ChangePasswordType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $newPassword = $request->request->all()["change_password"]['plainPassword']['first'];
            $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
            $user->setPassword($hashedPassword);
            $entityManager->flush();
//            dd($request);
           return $this->redirect($this->generateUrl('main_page'));
        }
        return $this->render('security/change.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
