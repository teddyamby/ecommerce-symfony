<?php

namespace App\Controller;

use App\Entity\ContactMessage;
use App\Form\ContactMessageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $message = new ContactMessage();
        $form = $this->createForm(ContactMessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setCreatedAt(new \DateTimeImmutable());
            
            $entityManager->persist($message);
            $entityManager->flush();

            $this->addFlash('success', 'Your message has been sent! We will contact you soon.');

            return $this->redirectToRoute('app_contact');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}