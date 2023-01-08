<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\Products;
use App\Entity\User;
use App\Repository\ProductsRepository;
use App\Services\FileUploader;
use App\Form\CreateType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
//#[Route('/user', name: 'user.')]
class MainPageController extends AbstractController
{

    #[Route('/', name: 'main_page')]
    public function index(UserRepository $userRepository, ProductsRepository $productsRepository): Response
    {
//        $title = "Tytuł";
        $user = $userRepository->findAll();
        $products = $productsRepository->findAll();
        return $this->render('main_page/index.html.twig', [
//            'title' => $title,
            'user' => $user,
            'products' => $products
        ]);
    }
    #[Route('/profile', name: 'profile')]
    public function profile(UserRepository $userRepository)
    {
        $user = $this->getUser();
//        dd($user);
        return $this->render('main_page/profile.html.twig', [
            'user' => $user
        ]);
    }

    #[Route('/products/{id}', name: 'products')]
    public function showProducts($id, ProductsRepository $productsRepository): Response
    {
        $products = $productsRepository->find($id);
//        $id =$request->attributes->all()["id"];
////        dd($request->attributes->all()["id"]);
//        $products = $this->
        return $this->render('main_page/show.html.twig', [
            "product" => $products
        ]);

    }

}
