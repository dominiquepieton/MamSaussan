<?php 
namespace App\Controller\Dynamic;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class AccountController extends AbstractController
{
    
    /**
     * Permet la connexion au site
     * @Route("/connexion", name="account_login")
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        return $this->render('account/login.html.twig', [
            'hasError' => $error !== null,
            'username' => $username
        ]);
    }

    /**
     * Deconnexion
     * @Route("/logout", name="account_logout")
     * @return void
     */
    public function logout()
    {

    }
}
?>