<?php 

namespace App\Controller\Admin;

use Doctrine\ORM\EntityManagerInterface;
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
    public function index(EntityManagerInterface $manager)
    {
        $users = $manager->createQuery('SELECT COUNT(a) FROM App\Entity\User a')->getSingleScalarResult();
        $articles = $manager->createQuery('SELECT COUNT(b) FROM App\Entity\Article b')->getSingleScalarResult();
        $comments = $manager->createQuery('SELECT COUNT(c) FROM App\Entity\Comment c')->getSingleScalarResult();
        
        return $this->render('admin/dashboard.html.twig',[
            'stats' => compact(
                'users',
                'articles',
                'comments'
                )
        ]);
    }


    
}


?>