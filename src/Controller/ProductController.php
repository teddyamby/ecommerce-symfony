<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\UserMessage;
use App\Form\ProductType;
use App\Form\UserMessageType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/product')]
class ProductController extends AbstractController
{
    #[Route('/', name: 'app_product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setCreatedAt(new \DateTimeImmutable());
            $product->setUpdatedAt(new \DateTimeImmutable());
            
            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash('success', 'Product created successfully!');

            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('product/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setUpdatedAt(new \DateTimeImmutable());
            $entityManager->flush();

            $this->addFlash('success', 'Product updated successfully!');

            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_product_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager->remove($product);
            $entityManager->flush();
            
            $this->addFlash('success', 'Product deleted successfully!');
        }

        return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/contact', name: 'app_product_contact_seller', methods: ['GET', 'POST'])]
    public function contactSeller(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        
        $message = new UserMessage();
        $form = $this->createForm(UserMessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setSender($this->getUser());
            $message->setReceiver($product->getUser());
            $message->setProduct($product);
            $message->setCreatedAt(new \DateTimeImmutable());
            $message->setIsRead(false);

            $entityManager->persist($message);
            $entityManager->flush();

            $this->addFlash('success', 'Your message has been sent to the seller!');

            return $this->redirectToRoute('app_product_show', ['id' => $product->getId()]);
        }

        return $this->render('product/contact_seller.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }
}