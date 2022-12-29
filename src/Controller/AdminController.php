<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Products;
use App\Entity\User;
use App\Form\CreateType;
use App\Form\MessageType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use App\Services\emailSend\EmailSend;
use App\Services\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use http\Params;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
class AdminController extends AbstractController
{
    #[Route('admin/create', name: 'admin_create')]
    public function create(UserRepository $userRepository, Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $products = new Products();
        $form = $this->createForm(CreateType::class, $products);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {


            $file = $request->files->get("create")["Attachment"];
            $filename = $fileUploader->uploadFiles($file);
            $products->setAttachment($filename);
            $entityManager->persist($products);
            $entityManager->flush();
            return $this->redirect($this->generateUrl('main_page'));
        }

        return $this->render('admin/create.html.twig', [
            'form' => $form->createView()
        ]);

    }
    #[Route('/admin/panel', name: 'admin')]
    public function panel(UserRepository $userRepository){
//            $user = $userRepository->findAll();
        $user = $this->getUser();

//        dd($user);
        return $this->render('admin/admin.html.twig',
            [
                'user' => $user
            ]);
    }



    #[Route('/admin/show', name: 'admin_show')]
    public function show(UserRepository $userRepository) {
            $users = $userRepository->findAll();

            return $this->render('admin/show.html.twig', [
                'users' => $users
            ]);
        }


//    public function email(UserRepository $userRepository,) {
//        $email = new EmailSend();
//        $email->send("TEST TEST TEST");
//
//    }
    private EmailVerifier $emailVerifier;
    private UserRepository $userRepository;
    public function __construct(EmailVerifier $emailVerifier, UserRepository $userRepository)
    {
        $this->emailVerifier = $emailVerifier;
        $this->userRepository = $userRepository;
        global $title;
    }

    #[Route('/admin/email/', name: 'admin_email')]

    public function email(Request $request) {
//        $request = new Request();
//        $message = new Message();
        $res = new Response();
        $form = $this->createForm(MessageType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            static $title;
            $title = $request->request->all("message")["Title"];
            $content = $request->request->all("message")["Content"];


//
            return $this->redirect($this->generateUrl('admin_send', ['title' => $title, 'content' => $content]));

        }

        return $this->render('admin/email.html.twig',[
            'form'=> $form->createView()
        ]);
    }

    #[Route('/admin/email/send', name: 'admin_send')]
    public function send(Request $request)
    {

        $user = $this->userRepository->findAll();

        $title = $request->query->all()["title"];
        $content = $request->query->all()["content"];
        foreach ($user as $el) {
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $el,
                (new TemplatedEmail())
                    ->from(new Address('testowedamian1@gmail.com', 'Testowy Mail Bot'))
                    ->to($el->getEmail())
                    ->subject('Mamy nową wiadomość dla ciebie')

//                    ->text("text")
                ->htmlTemplate('admin/message.html.twig')
                    ->context([
                        'title' => $title,
                        'content' => $content
                            ])
            );

        }


        return $this->redirect($this->generateUrl('admin'));

    }

}
