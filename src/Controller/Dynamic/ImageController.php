<?php
namespace App\Controller\Dynamic;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ImageController extends AbstractController
{
    /**
     * @Route("/image/index", name="image_index")
     *
     * @param UserRepository $repo
     * @return Response
     */
    function showImage(UserRepository $repo){

        $images = $repo->findAll();
        
        
        return $this->render('user/image/index.html.twig', [
            'images' => $images
        ]);
    }
}

?>