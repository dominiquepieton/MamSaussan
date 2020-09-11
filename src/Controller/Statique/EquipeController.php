<?php

namespace App\Controller\Statique;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class EquipeController extends AbstractController{
  
    /**
     * page presentation d'équipe
     * 
     * @Route("/equipe", name="equipe")
     * @return void
     */
    public function equipe(){
        return $this->render('static/equipe.html.twig');
    }

}