<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\User;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(ProductRepository $productRepository, UserRepository $userRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        return $this->render('admin/index.html.twig', [
            'products' => $productRepository->findAll(),
            'users' => $userRepository->findAll(),
        ]);
    }
}