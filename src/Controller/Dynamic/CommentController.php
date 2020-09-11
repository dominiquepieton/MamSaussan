<?php 

namespace App\Controller\Dynamic;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class CommentController extends AbstractController
{
    /**
     * Permet à l'utilisateur de voir les commentaires publiés
     *
     * @Route("/commentaire/index", name="comment_index")
     *  
     * @return void
     */
    public function allComment(CommentRepository $repo)
    {
        $comments = $repo->findAll();

        return $this->render('user/comment/commentIndex.html.twig',[
            'comments' => $comments
        ]);
    }

    /**
     * Permet la creation d'un commentaire
     *
     * @Route("/commentaire/new", name="comment_new")
     * @return Response
     */
    public function createComment(Request $request, EntityManagerInterface $manager, \Swift_Mailer $mailer)
    {
        $comment = new Comment();
        $comment->setPublish(false)
                ->setCreatedAt(new \DateTime('now'));

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $comments = $form->getData();

            $manager->persist($comment);
            $manager->flush();

            $message = (new \Swift_Message('Nouveau Commentaire'))
                ->setFrom($comments->getAuthor().'@mam.fr')
                ->setTo('pietondominique70@gmail.com')
            //creation du message avec la vue twig
                ->setBody(
                    $this->renderView(
                        'email/commentEmail.html.twig', compact('comments')
                    ),
                    'text/html'
                )
            ;

            $mailer->send($message);


            $this->addFlash(

                'success',
                "Votre commentaire dont le titre est <strong>{$comment->getTitle()}</strong> est enregistré,
                mais doit être valider par le modérateur... "

            );
            return $this->redirectToRoute('comment_index');
        }

        return $this->render('user/comment/commentNew.html.twig',[
            'form' => $form->createView()
        ]);    
    }
}


?>