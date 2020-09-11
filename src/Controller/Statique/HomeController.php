<?php

namespace App\Controller\Statique;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;


class HomeController extends AbstractController{

    /**
     * Page d'accueil
     * 
     * @Route("/", name="homepage")
     * @return void
     */ 
    public function home()
    {
        return $this->render('static/home.html.twig');
    }
}