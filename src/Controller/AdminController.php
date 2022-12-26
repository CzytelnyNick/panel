<?php

namespace App\Controller;

use App\Entity\Products;
use App\Entity\User;
use App\Form\CreateType;
use App\Repository\UserRepository;
use App\Services\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
    public function admin(Request $request){

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

}
