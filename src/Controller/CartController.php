<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Product;
use App\Repository\CartItemRepository;
use App\Repository\CartRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cart')]
class CartController extends AbstractController
{
    #[Route('/', name: 'app_cart_index', methods: ['GET'])]
    public function index(CartItemRepository $cartItemRepository): Response
    {
        $user = $this->getUser();
        
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $cart = $user->getCart();
        
        if (!$cart) {
            $cart = new Cart();
            $cart->setUser($user);
        }

        $cartItems = $cartItemRepository->findBy(['cart' => $cart]);

        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item->getProduct()->getPrice() * $item->getQuantity();
        }

        return $this->render('cart/index.html.twig', [
            'cartItems' => $cartItems,
            'total' => $total,
        ]);
    }

    #[Route('/add/{id}', name: 'app_cart_add', methods: ['POST'])]
    public function add(Product $product, Request $request, EntityManagerInterface $entityManager, CartRepository $cartRepository, CartItemRepository $cartItemRepository): Response
    {
        $user = $this->getUser();
        
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $quantity = $request->request->getInt('quantity', 1);

        $cart = $user->getCart();
        
        if (!$cart) {
            $cart = new Cart();
            $cart->setUser($user);
            $entityManager->persist($cart);
        }

        $cartItem = $cartItemRepository->findOneBy([
            'cart' => $cart,
            'product' => $product,
        ]);

        if ($cartItem) {
            $cartItem->setQuantity($cartItem->getQuantity() + $quantity);
        } else {
            $cartItem = new CartItem();
            $cartItem->setCart($cart);
            $cartItem->setProduct($product);
            $cartItem->setQuantity($quantity);
            $cartItem->setCreatedAt(new \DateTimeImmutable());
            $entityManager->persist($cartItem);
        }

        $entityManager->flush();

        $this->addFlash('success', 'Product added to cart!');

        return $this->redirectToRoute('app_cart_index');
    }

    #[Route('/remove/{id}', name: 'app_cart_remove', methods: ['POST'])]
    public function remove(CartItem $cartItem, Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cartItem->getId(), $request->request->get('_token'))) {
            $entityManager->remove($cartItem);
            $entityManager->flush();
            
            $this->addFlash('success', 'Product removed from cart!');
        }

        return $this->redirectToRoute('app_cart_index');
    }

    #[Route('/update/{id}', name: 'app_cart_update', methods: ['POST'])]
    public function update(CartItem $cartItem, Request $request, EntityManagerInterface $entityManager): Response
    {
        $quantity = $request->request->getInt('quantity', 1);
        
        if ($quantity > 0) {
            $cartItem->setQuantity($quantity);
            $entityManager->flush();
            
            $this->addFlash('success', 'Cart updated!');
        } else {
            $entityManager->remove($cartItem);
            $entityManager->flush();
            
            $this->addFlash('success', 'Product removed from cart!');
        }

        return $this->redirectToRoute('app_cart_index');
    }
}