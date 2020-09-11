<?php 

namespace App\Controller\Admin;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class AdminController extends AbstractController
{
    /**
     * Accés au dashboard
     *
     * @Route("/mymamAdmin/dashboard", name="admin_homepage")
     * @IsGranted("ROLE_ADMIN")
     * @return Response
     */
    public function index()
    {
        return $this->render('admin/dashboard.html.twig');
    }


    
}


?>