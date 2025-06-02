<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    #[Route('/account', name: 'app_account')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        
        return $this->render('account/index.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    #[Route('/account/edit', name: 'app_account_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('plainPassword')->getData()) {
                $user->setPassword(
                    $passwordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
            }
            
            $entityManager->flush();

            $this->addFlash('success', 'Your account has been updated!');

            return $this->redirectToRoute('app_account');
        }

        return $this->render('account/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}