<?php

namespace App\Controller\Statique;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FonctionController extends AbstractController{

    /**
     * Page du fonctionnement de la mam
     * @Route("/fonctionnement", name="fonction")
     * @return void
     */
    public function presentMam(){
        return $this->render('static/fonction.html.twig');
    }
}