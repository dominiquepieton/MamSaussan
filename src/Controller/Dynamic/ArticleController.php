<?php 

namespace App\Controller\Dynamic;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ArticleController extends AbstractController
{
    /**
     * Permet de faire le listing des articles
     *
     * @Route("/article/index", name="article_index")
     * @IsGranted("ROLE_USER")
     * @param ArticleRepository $repo
     * @return void
     */
    public function index(ArticleRepository $repo)
    {
        $articles = $repo->findAll();

        return $this->render('user/article/indexArticle.html.twig', [
            'articles' => $articles
        ]);
    }


    /**
     * Permet d'afficher un article
     * 
     * @Route("/article/{slug}", name="article_show")
     * @IsGranted("ROLE_USER")
     * 
     * @return Response
     */
    public function show(Article $article, ArticleRepository $repo)
    {
        $articles = $repo->findAll();

        return $this->render('user/article/show.html.twig',[
            'article'=> $article,
            'articles' => $articles
        ]);
    }
}

?>