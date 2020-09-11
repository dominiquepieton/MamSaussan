<?php

namespace App\Controller\Statique;

use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController Extends AbstractController{

    /**
     * Page de contact
     * @Route("/contact", name="contact")
     * @return void
     */
    public function contact(Request $request, \Swift_Mailer $mailer)
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $contact = $form->getData();

            $message = (new \Swift_Message('Nouveau Contact'))
                ->setFrom($contact['email'])
                ->setTo('pietondominique70@gmail.com')
            //creation du message avec la vue twig
                ->setBody(
                    $this->renderView(
                        'email/contactEmail.html.twig', compact('contact')
                    ),
                    'text/html'
                )
            ;

            $mailer->send($message);

            $this->addFlash('success', 'Votre email a bien été envoyé.');

            return $this->redirectToRoute('homepage');
        }
        
        return $this->render('static/contact.html.twig', [
            'contactForm' => $form->createView()
        ]);
    }
}