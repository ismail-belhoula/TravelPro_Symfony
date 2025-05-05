<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Entity\Client;
use App\Entity\DemandeValidation;
use App\Form\UtilisateurType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\DemandeValidationRepository;
use GuzzleHttp\Client as HttpClient;
class ProfileController extends AbstractController
{
    #[Route('/modifier-profil', name: 'app_modifier_profil', methods: ['POST'])]
    public function modifierProfil(Request $request, EntityManagerInterface $em, SessionInterface $session): Response
    {
        $idUtilisateur = $session->get('utilisateur_id');
        $idClient = $session->get('client_id');
    
        if (!$idUtilisateur || !$idClient) {
            $this->addFlash('error', 'Utilisateur non connecté.');
            return $this->redirectToRoute('app_login');
        }
    
        $utilisateur = $em->getRepository(Utilisateur::class)->find($idUtilisateur);
        $client = $em->getRepository(Client::class)->find($idClient);
    
        if (!$utilisateur || !$client) {
            $this->addFlash('error', 'Utilisateur ou client introuvable.');
            return $this->redirectToRoute('app_utilisateur_index');
        }
    
        try {
            $utilisateur->setNomUtilisateur($request->request->get('nom'));
            $utilisateur->setPrenom($request->request->get('prenom'));
            $utilisateur->setMailUtilisateur($request->request->get('email'));
    
            $client->setNum_Tel_Client($request->request->get('num'));
            $client->setAddresse_client($request->request->get('adresse'));
    
            $em->flush();
    
            // Mise à jour de la session
            $session->set('utilisateur_nom', $utilisateur->getNomUtilisateur());
            $session->set('utilisateur_prenom', $utilisateur->getPrenom());
            $session->set('utilisateur_email', $utilisateur->getMailUtilisateur());
            $session->set('client_telephone', $client->getNum_tel_client());
            $session->set('client_adresse', $client->getAddresse_client());
    
            $this->addFlash('success', 'Profil mis à jour avec succès !');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Erreur lors de la mise à jour du profil.');
        }
    
        return $this->redirectToRoute('app_utilisateur_index');
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(SessionInterface $session): Response
    {
        $session->clear(); // Vide toute la session
        return $this->redirectToRoute('./profile.html.twig'); // Change par la route correcte de ta page d'accueil
    }







/*
    #[Route('/envoyerdemende', name: 'envoyerdemende')]
    public function envoyerDemande(EntityManagerInterface $em, SessionInterface $session): Response
    {
        $clientId = $session->get('client_id');
        if (!$clientId) {
            $this->addFlash('error', 'Vous devez être connecté pour envoyer une demande.');
            return $this->redirectToRoute('app_login');
        }

        $client = $em->getRepository(Client::class)->find($clientId);
        if (!$client) {
            $this->addFlash('error', 'Client non trouvé.');
            return $this->redirectToRoute('app_home');
        }

        $demande = new DemandeValidation();
        $demande->setClient($client);
        $demande->setStatut('en attente');
        $demande->setDateDemande(new \DateTime());
        $demande->setDescription("Hola, ¿cómo estás?");
        $demande->setDescriptionFR("ayoub2");


        $em->persist($demande);
        $em->flush();

        $this->addFlash('success', 'Votre demande a bien été envoyée.');

        return $this->redirectToRoute('app_utilisateur_index'); // ou vers la page profil
    }

*/


 /*   
#[Route('/envoyerdemende', name: 'envoyerdemende')]
public function envoyerDemande(EntityManagerInterface $em, SessionInterface $session): Response
{
    $clientId = $session->get('client_id');
    if (!$clientId) {
        $this->addFlash('error', 'Vous devez être connecté pour envoyer une demande.');
        return $this->redirectToRoute('app_login');
    }

    $client = $em->getRepository(Client::class)->find($clientId);
    if (!$client) {
        $this->addFlash('error', 'Client non trouvé.');
        return $this->redirectToRoute('app_home');
    }

    $texteOriginal = "Hola, ¿cómo estás?";
    $traduction = "";

    try {
        $httpClient = new HttpClient();
        $response = $httpClient->request('POST', 'http://127.0.0.1:8080/traduire', [
            'json' => ['texte' => $texteOriginal]
        ]);

        $data = json_decode($response->getBody(), true);
        if (isset($data['traduction'])) {
            $traduction = $data['traduction'];
        }
    } catch (\Exception $e) {
        $this->addFlash('error', 'Erreur lors de la traduction automatique : ' . $e->getMessage());
        return $this->redirectToRoute('app_utilisateur_index');
    }

    $demande = new DemandeValidation();
    $demande->setClient($client);
    $demande->setStatut('En attente');
    $demande->setDateDemande(new \DateTime());
    $demande->setDescription($texteOriginal);
    $demande->setDescriptionFR($traduction);

    $em->persist($demande);
    $em->flush();

    $this->addFlash('success', 'Votre demande a bien été envoyée avec traduction.');

    return $this->redirectToRoute('app_utilisateur_index'); // ou vers une autre page
}
*/



#[Route('/envoyerdemende', name: 'envoyerdemende', methods: ['POST'])]
public function envoyerDemande(Request $request, EntityManagerInterface $em, SessionInterface $session): Response
{
    $clientId = $session->get('client_id');
    if (!$clientId) {
        $this->addFlash('error', 'Vous devez être connecté pour envoyer une demande.');
        return $this->redirectToRoute('app_login');
    }

    $client = $em->getRepository(Client::class)->find($clientId);
    if (!$client) {
        $this->addFlash('error', 'Client non trouvé.');
        return $this->redirectToRoute('app_home');
    }

    $texteOriginal = $request->request->get('description', '');
    $traduction = "";

    try {
        $httpClient = new HttpClient();
        $response = $httpClient->request('POST', 'http://127.0.0.1:8080/traduire', [
            'json' => ['texte' => $texteOriginal]
        ]);

        $data = json_decode($response->getBody(), true);
        if (isset($data['traduction'])) {
            $traduction = $data['traduction'];
        }
    } catch (\Exception $e) {
        $this->addFlash('error', 'Erreur lors de la traduction automatique : ' . $e->getMessage());
        return $this->redirectToRoute('app_utilisateur_index');
    }

    $demande = new DemandeValidation();
    $demande->setClient($client);
    $demande->setStatut('En attente');
    $demande->setDateDemande(new \DateTime());
    $demande->setDescription($texteOriginal);
    $demande->setDescriptionFR($traduction);

    $em->persist($demande);
    $em->flush();

    $this->addFlash('success', 'Votre demande a bien été envoyée avec traduction.');

    return $this->redirectToRoute('app_utilisateur_index');
}

    






    #[Route('/changer-statut/{id}', name: 'changer_statut_demande', methods: ['POST'])]
public function changerStatutDemande(int $id, Request $request, EntityManagerInterface $em): Response
{
    $demande = $em->getRepository(DemandeValidation::class)->find($id);

    if (!$demande) {
        $this->addFlash('error', 'Demande introuvable.');
        return $this->redirectToRoute('mes_demandes');
    }

    $nouveauStatut = $request->request->get('statut');
    $demande->setStatut($nouveauStatut);
    $em->flush();

    $this->addFlash('success', 'Statut mis à jour.');
    return $this->redirectToRoute('mes_demandes');
}



#[Route('/mes-demandes', name: 'mes_demandes')]
public function mesDemandes(SessionInterface $session, DemandeValidationRepository $repo): Response
{
    $clientId = $session->get('client_id');
    if (!$clientId) {
        $this->addFlash('error', 'Vous devez être connecté.');
        return $this->redirectToRoute('app_login');
    }

    $demandes = $repo->findBy(['client' => $clientId]);

    return $this->render('profile/mes_demandes.html.twig', [
        'demandes' => $demandes,
    ]);
}

}
