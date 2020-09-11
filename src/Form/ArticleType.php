<?php

namespace App\Form;

use App\Form\AppType;
use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ArticleType extends AppType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('title',
               TextType::class,
               $this->getConfig('Titre :', 'Entrez le titre....'))

        ->add('slug',
               TextType::class,
               $this->getConfig('Slug :' , 'EX: Mon-titre-exemple gérer automatiquement', [
                'required' => false
               ]))
        ->add('Description',
               TextType::class,
               $this->getConfig('Intro :', 'Intro de 200 caractères.'))

        ->add('content',
              TextareaType::class,
              $this->getConfig('Article : ', "Contenu de l'article."))

        ->add('imgArticles',
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
            'data_class' => Article::class,
        ]);
    }
}
