<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Entity\ImgArticle;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class AdminArticleController extends AbstractController
{

    /**
     * Permet de faire le listing des articles
     * @IsGranted("ROLE_ADMIN")
     * @Route("/mymamAdmin/article/index", name="admin_index_article")
     * 
     * @param ArticleRepository $repo
     * @return Response
     */
    public function articleIndex(ArticleRepository $repo)
    {

        $articles = $repo->findAll();

        return $this->render('admin/article/indexArticle.html.twig',[
            'articles' => $articles
        ]);
    }



    /**
     * Permet la creation d'un article
     * @IsGranted("ROLE_ADMIN")
     * @Route("/mymamAdmin/article/new", name="admin_create_article")
     * 
     *
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $manager)
    {
        
        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);   
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //on recupere les images
            $images = $form->get('imgArticles')->getData();

            //on boucle pour génerer un nouveau fichier
            foreach($images as $image){
                $file = md5(uniqid()). '.'.$image->guessExtension();
                    //on le copie dans le dossier upload
                $image->move(
                    $this->getParameter('images_directory'),
                    $file
                );
                    // on l'envoi dans la base de données
                $img = new ImgArticle();
                $img->setUrl($file);
                $img->setCaption('photo aleatoire');
                $article->addImgArticle($img);
            }

            $manager->persist($article);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'article <strong>{$article->getTitle()}</strong> a bien étè enregistrée."
            );

            return $this->redirectToRoute('admin_index_article');
        }

        return $this->render('/admin/article/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet la correction d'un article
     * @IsGranted("ROLE_ADMIN")
     * @Route("/mymamAdmin/article/edit/{id}", name="admin_edit_article")
     * 
     * @return Response
     */
    public function edit(Article $article, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
 
        if ($form->isSubmitted() && $form->isValid()){
            
            //on recupere les images
            $images = $form->get('imgArticles')->getData();

            //on boucle pour génerer un nouveau fichier
            foreach($images as $image){
                $file = md5(uniqid()). '.'.$image->guessExtension();
                    //on le copie dans le dossier upload
                $image->move(
                    $this->getParameter('images_directory'),
                    $file
                );
                    // on l'envoi dans la base de données
                $img = new ImgArticle();
                $img->setUrl($file);
                $img->setCaption('photo aleatoire');
                $article->addImgArticle($img);
            }

            $manager->persist($article);
            $manager->flush();
                   
            $this->addFlash(
                'success',
                "Les modifications de l'article dont le titre est <strong>{$article->gettitle()}</strong> ont bien étè enregistrè."
            );
             
            return $this->redirectToRoute('admin_index_article');
        }
    
        return $this->render('admin/article/editArticle.html.twig', [
            'form' =>$form->createView(),
            'article'=> $article
        ]);
    }

    /**
     * Permet de supprimer un article
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/article/{id}/delete", name="admin_article_delete")
     * 
     * 
     * @return Response
     */
    public function delete(Article $article, EntityManagerInterface $manager)
    {
        $manager->remove($article);
        $manager->flush();

        $this->addFlash(
            'success',
            "L'article a bien été supprimée !"
        );

        return $this->redirectToRoute("admin_index_article");

    }

    /**
    * @Route("/supprime/image/{id}", name="annonces_delete_image", methods={"DELETE"})
    * @IsGranted("ROLE_ADMIN")
    */
    public function deleteImg(ImgArticle $image, Request $request)
    {
        $data = json_decode($request->getContent(), true);

        //vérification du token est valide
                // On récupère le nom de l'image
            $nom = $image->getUrl();
                // On supprime le fichier
            unlink($this->getParameter('images_directory').'/'.$nom);

                // On supprime l'entrée de la base
            $em = $this->getDoctrine()->getManager();
            $em->remove($image);
            $em->flush();

                // On répond en json
            return new JsonResponse(['success' => 1]);
    }
}

?>