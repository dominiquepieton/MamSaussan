<?php 

namespace App\Controller\Admin;

use App\Entity\Comment;
use App\Form\AdminCommentType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommentController extends AbstractController
{   
    /**
     * Permet à l'administrateur de voir les commentaires publiés
     * @IsGranted("ROLE_ADMIN")
     * @Route("/mymamAdmin/commentaire/index", name="admin_comment_index")
     *  
     * @return Response
     */
    public function allComment(CommentRepository $repo)
    {
        $comments = $repo->findAll();

        return $this->render('admin/comment/commentIndex.html.twig',[
            'comments' => $comments
        ]);
    }

    /**
     * Permet la correction d'un commentaire et de le publier
     * @Route("/mymamAdmin/commentaire/edit/{id}", name="admin_edit_comment")
     * @IsGranted("ROLE_ADMIN")
     * 
     * @param Comment $comment
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function editComment(Comment $comment, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(AdminCommentType::class, $comment);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $manager->persist($comment);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les modifications ont bien étè enregistré..."
            );

            return $this->redirectToRoute('admin_comment_index');

        }

        return $this->render('admin/comment/commentEdit.html.twig', [
            'form' =>$form->createView()
        ]);
    }

    /**
     * Permet de supprimer un commentaire
     *
     * @Route("/admin/comment/{id}/delete", name="admin_comment_delete")
     * @IsGranted("ROLE_ADMIN")
     * @param Comment $comment
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function deleteComment(Comment $comment, EntityManagerInterface $manager)
    {
        $manager->remove($comment);
        $manager->flush();

        $this->addFlash(
            'success',
            "Le commentaire a bien été supprimée !"
        );

        return $this->redirectToRoute("admin_comment_index");
    }
}
?>