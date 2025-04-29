<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use App\Repository\ProduitRepository;
use Cloudinary\Cloudinary;
use Cloudinary\Api\Exception\ApiError;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
// Ajoutez ce use statement avec les autres imports
use App\Entity\Client;
use App\Entity\Produit;
use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use SebastianBergmann\Environment\Console;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\DemandeValidationRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

#[Route('/utilisateur')]
final class UtilisateurController extends AbstractController
{



/*    
    #[Route('/profile', name: 'app_profile')]
public function profile(): Response
{
    return $this->render('./profile.html.twig');
}
*/
#[Route('/profile', name: 'app_profile')]
public function profile(SessionInterface $session, DemandeValidationRepository $repo,ClientRepository $clientRepository,ProduitRepository $PR): Response
{
    $clientId = $session->get('client_id');
    $demandes = [];
    $clients=[];
    $produits=[];

    if ($clientId) {
        $demandes = $repo->findAll();
        $clients = $clientRepository->findAll(); // ou findByUser() si tu veux filtrer
        $produits= $PR->findAll();
    }

    return $this->render('profile.html.twig', [
        'demandes' => $demandes,
        'clients' => $clients,
       // 'produits' => $produits,
        'produits' => [],
    ]);
}







    #[Route(name: 'app_utilisateur_index', methods: ['GET'])]
    public function index(UtilisateurRepository $utilisateurRepository): Response
    {
        return $this->render('index.html.twig', [
            'utilisateurs' => $utilisateurRepository->findAll(),
        ]);
    }

   



    #[Route('/inscription', name: 'app_inscription', methods: ['GET', 'POST'])]
    public function register(
        Request $request, 
        EntityManagerInterface $entityManager, 
        SessionInterface $session, 
        MailerInterface $mailer
    ): Response {
        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hash du mot de passe
            $hashedPassword = password_hash(
                $utilisateur->getMotDePasseUtilisateur(), 
                PASSWORD_DEFAULT
            );
            $utilisateur->setMotDePasseUtilisateur($hashedPassword);
            $utilisateur->setRoleUtilisateur('Client');

            // Sauvegarde de l'utilisateur





             $code = '';
            for ($i = 0; $i < 6; $i++) {
                $code .= random_int(0, 9);
            }
        
            




            $utilisateur->setCodeVerification($code);
            $utilisateur->setEtat(false);


            $entityManager->persist($utilisateur);
            $entityManager->flush();

            // Création du client
            $client = new \App\Entity\Client();
            $client->setAddresse_client($form->get('adresse')->getData());
            $client->setNum_tel_client($form->get('telephone')->getData());
            $client->setImage_url("https://static.thenounproject.com/png/1743561-200.png");

            $client->setUtilisateur($utilisateur);
            
            $entityManager->persist($client);
            $entityManager->flush();

            // Envoi d'email
            $this->sendWelcomeEmail($mailer, $utilisateur, $session);

            // Initialisation de la session
            $this->initUserSession($session, $utilisateur, $client);

            return $this->redirectToRoute('app_utilisateur_index');
        }

        return $this->render('utilisateur/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function sendWelcomeEmail(
        MailerInterface $mailer, 
        Utilisateur $utilisateur, 
        SessionInterface $session
    ): void {
        try {
            $email = (new Email())
                ->from(new Address('ayoubbelgacim@gmail.com', 'TravelPro'))
                ->to(new Address(
                    $utilisateur->getMailUtilisateur(), 
                    $utilisateur->getPrenom().' '.$utilisateur->getNomUtilisateur()
                ))
                ->subject('Code de vérification pour valider votre compte')
                ->html($this->renderView('emails/welcome.html.twig', [
                    'user' => $utilisateur,
                    'code' => $utilisateur->getCodeVerification()
                ]));
    
            $mailer->send($email);
            $this->addFlash('success', 'Email de confirmation envoyé !');
            $session->set('email_sent', true);
        } catch (TransportExceptionInterface $e) {
            $this->addFlash('warning', 'L\'email n\'a pas pu être envoyé. Veuillez réessayer plus tard.');
            $session->set('email_sent', false);
            error_log('Mailer Error: '.$e->getMessage());
        }
    }
    
    private function initUserSession(
        SessionInterface $session, 
        Utilisateur $utilisateur, 
        \App\Entity\Client $client
    ): void {
        $session->set('utilisateur_id', $utilisateur->getIdUtilisateur());
        $session->set('utilisateur_nom', $utilisateur->getNomUtilisateur());
        $session->set('utilisateur_prenom', $utilisateur->getPrenom());
        $session->set('utilisateur_email', $utilisateur->getMailUtilisateur());
        $session->set('utilisateur_role', $utilisateur->getRoleUtilisateur());
        $session->set('code_verification', $utilisateur->getCodeVerification());
        $session->set('utilisateur_etat', $utilisateur->isEtat());

        $session->set('client_id', $client->getId_client());
        $session->set('client_adresse', $client->getAddresse_client());
        $session->set('client_telephone', $client->getNum_tel_client());
        $session->set('client_image', $client->getImage_url());

    }












    

    #[Route('/connexion', name: 'app_login', methods: ['GET', 'POST'])]
    public function login(Request $request, EntityManagerInterface $em, SessionInterface $session): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $password = $request->request->get('password');
    
            $utilisateur = $em->getRepository(Utilisateur::class)->findOneBy(['mail_utilisateur' => $email]);
    
            if (!$utilisateur || !password_verify($password, $utilisateur->getMotDePasseUtilisateur())) {
                $this->addFlash('error', 'Adresse e-mail ou mot de passe incorrect.');
            } else {
                // Stocker les infos de l'utilisateur
                $session->set('utilisateur_id', $utilisateur->getIdUtilisateur());
                $session->set('utilisateur_nom', $utilisateur->getNomUtilisateur());
                $session->set('utilisateur_prenom', $utilisateur->getPrenom());
                $session->set('utilisateur_email', $utilisateur->getMailUtilisateur());
                $session->set('utilisateur_role', $utilisateur->getRoleUtilisateur());//
                // Si l'utilisateur est un client, on stocke aussi son id_client
                $client = $em->getRepository(\App\Entity\Client::class)->findOneBy(['utilisateur' => $utilisateur]);
                if ($client) {
                    $session->set('client_id', $client->getId_client());
                    $session->set('client_adresse', $client->getAddresse_client());
                    $session->set('client_telephone', $client->getNum_tel_client());
                    $session->set('client_image', $client->getImage_url());
                }
    
                // Message de bienvenue
                $this->addFlash('success', 'Bienvenue, ' . $utilisateur->getPrenom() . ' !');
    
                return $this->redirectToRoute('app_utilisateur_index');
            }
        }
        return $this->render('utilisateur/connecter.html.twig');
        // return $this->render('utilisateur/emailforget.html.twig');
    }
    
    

    #[Route('/deconnexion', name: 'app_logout', methods: ['GET'])]
    public function logout(SessionInterface $session): Response
    {
        $session->remove('utilisateur_id');
        $session->clear(); // Vide toute la session

        return $this->redirectToRoute('app_utilisateur_index');
    }

    #[Route('/{id_utilisateur}', name: 'app_utilisateur_show', methods: ['GET'])]
    public function show(Utilisateur $utilisateur): Response
    {
        return $this->render('utilisateur/connecter.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }
/*
    #[Route('/profil/edit', name: 'app_profile_edit', methods: ['GET', 'POST'])]
    public function edit(SessionInterface $session, EntityManagerInterface $em, Request $request): Response
    {
        $userId = $session->get('utilisateur_id');

        if (!$userId) {
            $this->addFlash('error', 'Vous devez être connecté.');
            return $this->redirectToRoute('app_login');
        }

        $utilisateur = $em->getRepository(Utilisateur::class)->find($userId);
        $client = $em->getRepository(Client::class)->findOneBy(['utilisateur' => $utilisateur]);

        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $adresse = $form->get('adresse')->getData();
            $telephone = $form->get('telephone')->getData();

            if ($client) {
                $client->setAddresseClient($adresse);
                $client->setNumTelClient($telephone);
                $em->persist($client);
            }

            $em->flush();

            $this->addFlash('success', 'Profil mis à jour avec succès !');
            return $this->redirectToRoute('app_profil');
        }

        return $this->render('profil/edit.html.twig', [
            'form' => $form->createView(),
            'utilisateur' => $utilisateur,
            'client' => $client,
        ]);
    }

*/
#[Route('/profil/edit', name: 'app_modifier_profil', methods: ['POST'])]
public function modifierProfil(
    SessionInterface $session, 
    EntityManagerInterface $em, 
    Request $request,
    ParameterBagInterface $params
): Response {
    // Debug: vérifier les données reçues
    error_log('Données POST: '.print_r($request->request->all(), true));
    error_log('Fichiers: '.print_r($request->files->all(), true));

    $userId = $session->get('utilisateur_id');
    if (!$userId) {
        $this->addFlash('error', 'Authentification requise');
        return $this->redirectToRoute('app_login');
    }

    $utilisateur = $em->getRepository(Utilisateur::class)->find($userId);
    $client = $em->getRepository(Client::class)->findOneBy(['utilisateur' => $utilisateur]);

    if (!$utilisateur || !$client) {
        throw $this->createNotFoundException('Utilisateur non trouvé');
    }

    // Gestion de l'image
    $uploadedFile = $request->files->get('avatar');
    if ($uploadedFile) {
        try {
            $cloudinary = new Cloudinary([
                'cloud' => [
                    'cloud_name' => $params->get('cloudinary.cloud_name'),
                    'api_key' => $params->get('cloudinary.api_key'),
                    'api_secret' => $params->get('cloudinary.api_secret')
                ]
            ]);

            $result = $cloudinary->uploadApi()->upload(
                $uploadedFile->getRealPath(),
                [
                    'public_id' => 'user_'.$userId.'_avatar',
                    'overwrite' => true,
                    'invalidate' => true
                ]
            );

            // CORRECTION ICI: Utilisez setImage_url() au lieu de setId_client()
            $client->setImage_url($result['secure_url']);
            $session->set('client_image', $result['secure_url']);

        } catch (\Exception $e) {
            $this->addFlash('error', 'Erreur upload image: '.$e->getMessage());
            error_log('Erreur Cloudinary: '.$e->getMessage());
        }
    }

    // Mise à jour des autres champs
    $utilisateur
        ->setNomUtilisateur($request->request->get('nom'))
        ->setPrenom($request->request->get('prenom'))
        ->setMailUtilisateur($request->request->get('email'));

    $client
        ->setNum_tel_client($request->request->get('num'))
        ->setAddresse_client($request->request->get('adresse'));


    $em->flush();

    $session->set('utilisateur_nom', $utilisateur->getNomUtilisateur());
    $session->set('utilisateur_prenom', $utilisateur->getPrenom());
    $session->set('client_telephone', $client->getNum_tel_client());
    $session->set('client_adresse', $client->getAddresse_client());
    $session->set('utilisateur_email', $utilisateur->getMailUtilisateur());


    $this->addFlash('success', 'Profil mis à jour!');
    return $this->redirectToRoute('app_profile');
}













   




    
#[Route('/delete-mon-compte', name: 'app_utilisateur_delete', methods: ['POST'])]
public function delete(
    Request $request,
    SessionInterface $session,
    EntityManagerInterface $entityManager
): Response {
    $utilisateurId = $session->get('utilisateur_id');
    $clientId = $session->get('client_id');

    if (!$utilisateurId || !$clientId) {
        throw $this->createAccessDeniedException('Utilisateur ou client non trouvé dans la session.');
    }

    $utilisateur = $entityManager->getRepository(Utilisateur::class)->find($utilisateurId);
    $client = $entityManager->getRepository(\App\Entity\Client::class)->find($clientId);

    if (!$utilisateur || !$client) {
        throw $this->createNotFoundException('Utilisateur ou Client introuvable.');
    }

    if ($this->isCsrfTokenValid('delete_compte', $request->getPayload()->getString('_token'))) {
        $entityManager->remove($client);
        $entityManager->remove($utilisateur);
        $entityManager->flush();

        $session->clear();
        $session->invalidate();

        $this->addFlash('success', 'Votre compte a bien été supprimé.');
        return $this->redirectToRoute('app_login');
    }

    $this->addFlash('error', 'Token CSRF invalide.');
    return $this->redirectToRoute('app_profile');
}





    #[Route('/profil', name: 'profil')]
    public function profil(DemandeRepository $demandeRepository,ClientRepository $clientRepository): Response
    {
        $demandes = $demandeRepository->findAll(); // ou findByUser() si tu veux filtrer
        $clients = $clientRepository->findAll(); // ou findByUser() si tu veux filtrer

        return $this->render('profile.html.twig', [
            'demandes' => $demandes,
            'clients' => $clients,

        ]);
    }





/*



#[Route('/mot_de_passe_oublie', name: 'app_utilisateur_forgot_password', methods: ['GET', 'POST'])]
public function forgotPassword(Request $request, EntityManagerInterface $em, MailerInterface $mailer, SessionInterface $session): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
    
            // Recherche de l'utilisateur par email
            $utilisateur = $em->getRepository(Utilisateur::class)->findOneBy(['mail_utilisateur' => $email]);
            
        if (!$utilisateur) {
            $this->addFlash('error', 'Aucun compte trouvé avec cette adresse email.');
            return $this->redirectToRoute('app_utilisateur_index');
        }
    
            if ($utilisateur) {
                // Génération d’un code de vérification à 6 chiffres
                $code = '';
                for ($i = 0; $i < 6; $i++) {
                    $code .= random_int(0, 9);
                }
    
                // Mise à jour du code dans l'utilisateur
                $utilisateur->setCodeVerification($code);
                $em->flush();
    
                 // Envoi d'email
            $this->sendWelcomeEmail($mailer, $utilisateur, $session);

                // Sauvegarde dans la session
                $session->set('utilisateur_id', $utilisateur->getIdUtilisateur());
                $session->set('code_verification', $code);
    
                // Redirection vers la page de vérification

                return $this->redirectToRoute('app_verification');
            } else {
                $this->addFlash('warning', 'Aucun compte n\'existe avec cette adresse email.');
                return $this->redirectToRoute('app_inscription');
            }
        }
    
        return $this->render('utilisateur/verification.html.twig');
    }
    
*/

#[Route('/mot_de_passe_oublie', name: 'app_utilisateur_forgot_password', methods: ['GET', 'POST'])]
public function forgotPassword(
    Request $request,
    EntityManagerInterface $em,
    MailerInterface $mailer,
    SessionInterface $session
): Response {
    if ($request->isMethod('POST')) {
        $email = $request->request->get('email');

        // Vérifie si l'email existe
        $utilisateur = $em->getRepository(Utilisateur::class)->findOneBy(['mail_utilisateur' => $email]);

        if (!$utilisateur) {
            $this->addFlash('error', 'Aucun compte associé à cette adresse email.');
            return $this->render('utilisateur/emailforget.html.twig', [
                'last_email' => $email,
            ]);
        }

        // Génère un code de vérification
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $utilisateur->setCodeVerification($code);
        $em->flush();

        // Stocke en session
        $session->set('reset_email', $email);
        $session->set('reset_code', $code);

        try {
            // Envoie l'email
            $emailMessage = (new Email())
                ->from(new Address('ayoubbelgacim@gmail.com', 'TravelPro'))
                ->to($utilisateur->getMailUtilisateur())
                ->subject('Réinitialisation de votre mot de passe')
                ->html($this->renderView('emails/reset_password.html.twig', [
                    'user' => $utilisateur,
                    'code' => $code,
                ]));

            $mailer->send($emailMessage);

            $this->addFlash('success', 'Un email avec un code de vérification vous a été envoyé.');
            return $this->redirectToRoute('app_verification'); // Redirige vers la page de vérification
        } catch (\Exception $e) {
            $this->addFlash('error', 'Erreur lors de l\'envoi du code. Veuillez réessayer.');
            return $this->render('utilisateur/emailforget.html.twig', [
                'last_email' => $email,
            ]);
        }
    }

    // Si méthode GET, affiche simplement le formulaire
    return $this->render('utilisateur/emailforget.html.twig');
}







    
    #[Route('/verification', name: 'app_verification', methods: ['GET', 'POST'])]
    public function verification(Request $request, SessionInterface $session, EntityManagerInterface $em): Response
    {
        if ($request->isMethod('POST')) {
            $code = $request->request->get('verification_code');
            $expectedCode = $session->get('code_verification');
            $utilisateurId = $session->get('utilisateur_id');
    
            if ($code === $expectedCode && $utilisateurId) {
                $utilisateur = $em->getRepository(Utilisateur::class)->find($utilisateurId);
                if ($utilisateur) {
                    $utilisateur->setEtat(true);
                    $em->flush();
    
                    // Mise à jour de la session
                    $session->set('utilisateur_etat', true);
    
                    $this->addFlash('success', 'Code vérifié avec succès !');
                    return $this->redirectToRoute('app_utilisateur_index');
                }
            }
    
            $this->addFlash('error', 'Code incorrect. Veuillez réessayer.');
        }
    
        return $this->render('utilisateur/verification.html.twig');
    }
    /*
    #[Route('/demande/update-statut/{id}', name: 'app_demande_update_statut', methods: ['POST'])]
    public function updateStatutDemande(
        Request $request,
        EntityManagerInterface $em,
        int $id,
        SessionInterface $session
    ): Response {
        // Vérification de l'authentification
        if (!$session->get('utilisateur_id')) {
            $this->addFlash('error', 'Vous devez être connecté pour effectuer cette action.');
            return $this->redirectToRoute('app_login');
        }
    
        $demande = $em->getRepository(DemandeValidation::class)->find($id);
        
        if (!$demande) {
            throw $this->createNotFoundException('Demande non trouvée');
        }
    
        // Vérification CSRF
        if (!$this->isCsrfTokenValid('update-statut-'.$id, $request->request->get('_token'))) {
            $this->addFlash('error', 'Token CSRF invalide.');
            return $this->redirectToRoute('app_profile');
        }
    
        $nouveauStatut = $request->request->get('statut');
        
        if (!in_array($nouveauStatut, ['Accepte', 'Refuse'])) {
            $this->addFlash('error', 'Statut invalide.');
            return $this->redirectToRoute('app_profile');
        }
    
        // Mise à jour du statut
        $demande->setStatut($nouveauStatut);
        $em->flush();
    
        $this->addFlash('success', 'Statut de la demande mis à jour avec succès !');
        return $this->redirectToRoute('app_profile');
    }*/



}
