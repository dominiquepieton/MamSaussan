<?php

namespace App\Controller\Statique;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProjectController extends AbstractController{

    /**
     * Page du projet de la MAM
     *
     * @Route("/projet", name="projet")
     * @return void
     */
    public function project()
    {
        return $this->render('static/project.html.twig');
    }
}