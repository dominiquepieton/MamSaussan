<?php

namespace App\Form;

use App\Form\AppType;
use Symfony\Component\Form\FormBuilderInterface;
use Domi\RecaptchaBundle\Type\RecaptchaSubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AppType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, $this->getConfig('Nom','Entrez votre nom'))
            ->add('prenom', TextType::class, $this->getConfig('Prenom','Entrez votre Prénom'))
            ->add('email', EmailType::class, $this->getConfig('Email','Entrez votre email'))
            ->add('message',TextareaType::class, $this->getConfig('Message','Entrez votre message'))
            ->add('captcha',RecaptchaSubmitType::class, [
                'label' => 'envoyer',
                
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
