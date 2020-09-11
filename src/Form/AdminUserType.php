<?php

namespace App\Form;

use App\Entity\User;
use App\Form\AppType;
use App\Form\ImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AdminUserType extends AppType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'firstName',
                TextType::class,
                 $this->getConfig('Prénom','Entrez le prénom')
            )
            ->add(
                'lastName',
                TextType::class, 
                $this->getConfig('Nom','Entrez le nom')
            )
            ->add(
                'email',
                EmailType::class, 
                $this->getConfig('Email',"Entrez l'email de l'utilisateur")
            )
            ->add(
                'nameChild',
                TextType::class, 
                $this->getConfig("Prénom de l'enfant","Entrez le prénom de l'enfant")
            )
            ->add(
                'hash',
                PasswordType::class,
                $this->getConfig('password','Entrez le password')
            )
            ->add(
                'passwordConfirm',
                PasswordType::class,
                $this->getConfig('Confirmation du password', "Veuillez confirmer le mot de passe")
            )
            ->add('images',
               FileType::class,[
                   'label' => 'images :',
                   'multiple' => true,
                   'mapped' => false,
                   'required' => false
               ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
