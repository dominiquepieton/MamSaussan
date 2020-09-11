<?php

namespace App\Form;

use App\Form\AppType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class PasswordUpdateType extends AppType
{
   

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword', PasswordType::class, $this->getConfig('votre password', 'entrez le mot de passe actuel'))
            ->add('newPassword', PasswordType::class, $this->getConfig('new password', 'entrez le nouveau password'))
            ->add('confirmPassword', PasswordType::class, $this->getConfig('confirm password', 'confirmez le password'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
