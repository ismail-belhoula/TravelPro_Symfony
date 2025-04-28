<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('index.html.twig');
    }

    #[Route('/about', name: 'about')]
    public function about(): Response
    {
        return $this->render('about.html.twig');
    }

    #[Route('/contact', name: 'contact')]
    public function contact(): Response
    {
        return $this->render('contact-us.html.twig');
    }

    #[Route('/typography', name: 'typography')]
    public function typography(): Response
    {
        return $this->render('typography.html.twig');
    }

    #[Route('/connexion', name: 'app_connexion')]
    public function login(): Response
    {
        return $this->render('./utilisateur/connecter.html.twig');
    }
    
    #[Route('/inscription', name: 'app_register')]
    public function register(): Response
    {
        return $this->render('./utilisateur/new.html.twig');
    }
    
    #[Route('/mot_de_passe_oublie', name: 'app_utilisateur_forgot_password')]
    public function forgotPassword(): Response
    {
      //  return $this->render('./utilisateur/emailforget.html.twig');
      return $this->render('index.html.twig');

    }

}
