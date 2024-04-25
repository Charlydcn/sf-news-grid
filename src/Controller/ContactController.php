<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            if(!$form->isValid()) {
                $errors = $form->getErrors(true, false);
                foreach($errors as $error) {
                    $errorMsg = $error->getMessage();
                    $this->addFlash('error', $errorMsg);
                    return $this->redirectToRoute('contact');
                }
            }

            $data = $form->getData();

            $email = (new Email())
            ->from('noreply@yourdomain.com')
            ->to('yourrecipient@domain.com')
            ->subject('New Contact Request')
            ->html($this->renderView('emails/contact.html.twig', [
                'data' => $data
            ]));

            $mailer->send($email);

            $this->addFlash('success', 'Your message has been sent!');

            return $this->redirectToRoute('contact');
        }

        return $this->render('contact.html.twig', [
            'contactForm' => $form->createView(),
        ]);
    }
}
