<?php

namespace App\Controller\Statique;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PresentController extends AbstractController{


    /**
     * Page de presentation de la MAM
     * 
     * @Route("/presentation", name="presentation")
     *
     * @return void
     */
    public function presentMam()
    {
        return $this->render('static/presentation.html.twig');
    }
}