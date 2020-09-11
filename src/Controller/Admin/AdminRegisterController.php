<?php 

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Image;
use App\Form\AdminUserType;
use App\Entity\PasswordUpdate;
use App\Form\PasswordUpdateType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;



class AdminRegisterController extends AbstractController 
{
    /**
     * Permet d'afficher la liste d'user
     *
     * @Route("/mymamAdmin/user/index", name="admin_user_index")
     * @IsGranted("ROLE_ADMIN")
     * @param UserRepository $repo
     * @return void
     */
    public function indexUser(UserRepository $repo)
    {
        $users = $repo->findAll();

        return $this->render('admin/user/index.html.twig',[
            'users' => $users,
        ]);
    }


    /**
     * Permet la creation d'un utilisateur
     * 
     * @Route("/mymamAdmin/user/new", name="admin_create_user")
     * @IsGranted("ROLE_ADMIN")
     *
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $manager, \Swift_Mailer $mailer, UserPasswordEncoderInterface $encoder)
    {
        
        $user = new User();

        $form = $this->createForm(AdminUserType::class, $user);   
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $users = $form->getData();
            //$manager = $this->getDoctrine()->getManager();
            $message = (new \Swift_Message('Votre inscription pour la MAM'))
                ->setFrom('pietondominique70@gmail.com')
                ->setTo($users->getEmail())
            //creation du message avec la vue twig
                ->setBody(
                    $this->renderView(
                        'email/userEmail.html.twig', compact('users')
                    ),
                    'text/html'
                )
            ;

            $mailer->send($message);
            
            $images = $form->get('images')->getData();
            foreach($images as $image){
                $file = md5(uniqid()). '.'.$image->guessExtension();
                    //on le copie dans le dossier upload
                $image->move(
                    $this->getParameter('images_directory'),
                    $file
                );
                    // on l'envoi dans la base de données
                $img = new Image();
                $img->setUrl($file);
                $img->setCaption("photo d'enfant");
                $user->addImage($img);
            }


            $hash = $encoder->encodePassword($user, $user->getHash());
            $user->setHash($hash);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Mr ou Madame <strong>{$user->getLastName()}  {$user->getFirstName()}</strong> a bien étè enregistrée."
            );

            return $this->redirectToRoute('admin_user_index');
        }

        return $this->render('/admin/user/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de corriger le profile de l'utilisateur
     * @IsGranted("ROLE_ADMIN")
     * @Route("/mymAdmin/user/edit/{id}", name="admin_user_edit")
     * @return Response
     */
    public function edit(User $user, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(AdminUserType::class, $user);
        $form->handleRequest($request);

        $users = $user->getHash();
 
        if ($form->isSubmitted() && $form->isValid()){
            $images = $form->get('images')->getData();
            foreach($images as $image){
                $file = md5(uniqid()). '.'.$image->guessExtension();
                    //on le copie dans le dossier upload
                $image->move(
                    $this->getParameter('images_directory'),
                    $file
                );
                    // on l'envoi dans la base de données
                $img = new Image();
                $img->setUrl($file);
                $img->setCaption("photo d'enfant");
                $user->addImage($img);
            }

            $manager->persist($user);
            $manager->flush();
                   
            $this->addFlash(
                'success',
                "Les modifications de l'utilisateur dont le nom est <strong>{$user->getLastName()}</strong> ont bien étè enregistrè."
            );
             
            return $this->redirectToRoute('admin_user_index');
        }
    
        return $this->render('admin/user/editUser.html.twig', [
            'form' =>$form->createView(),
            'user' => $user,
            'users' => $users
        ]);
    }

    /**
     * Suprime un utilisateur
     * @IsGranted("ROLE_ADMIN")
     * @Route("/mymamAdmin/user/{id}/delete", name="admin_user_delete")
     * @return Response
     */
    public function delete(User $user, EntityManagerInterface $manager)
    {
        $manager->remove($user);
        $manager->flush();

        $this->addFlash(
            'success',
            "L'utilisateur a bien été supprimé !"
        );

        return $this->redirectToRoute('admin_user_index');
    }

    /**
    * @Route("/supprime/photo/{id}", name="user_delete_image", methods={"DELETE"})
    * @IsGranted("ROLE_ADMIN")
    */
    public function deleteImg(Image $image, Request $request)
    {
        $data = json_decode($request->getcontent(), true);

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

    /**
     * Modifier le mot de passe
     * 
     * @Route("/mymAdmin/user/update-password/{id}", name="admin_user_updatePassword")
     * @IsGranted("ROLE_ADMIN") 
     * @return Response
     */
    public function updatePassword(User $user,Request $request,EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder, \Swift_Mailer $mailer)
    {
        $passwordUpdate = new PasswordUpdate();

        $users = $user->getHash();
        $email = $user->getEmail();

        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);
        $form->handleRequest($request);



        if($form->isSubmitted() && $form->isValid()){

            $newPass = $form->getData();
            
            $message = (new \Swift_Message('Votre nouveau password'))
                ->setFrom('pietondominique70@gmail.com')
                ->setTo($email)
            //creation du message avec la vue twig
                ->setBody(
                    $this->renderView(
                        'email/newPasswordEmail.html.twig', compact('newPass')
                    ),
                    'text/html'
                )
            ;

            $mailer->send($message);


            $newPassword = $passwordUpdate->getNewPassword();
            $hash = $encoder->encodePassword($user, $newPassword);

            $user->setHash($hash);
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre password a été modifié'
            );

            return $this->redirectToRoute('admin_user_index');
        }
        return $this->render('admin/user/updatePassword.html.twig',[
            'form' => $form->createView(),
            'users' => $users
        ]);
    }
}
?>