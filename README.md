# 🌍 TravelPro

<p align="center">
  <img src="banner.png" alt="TravelPro Screenshot" width="300">
</p>

**TravelPro** est une plateforme web centralisée pour la gestion des déplacements professionnels, offrant une solution complète pour la réservation, la gestion des itinéraires, la gestion des avis, des finances, des réservations, des événements et des boutiques. TravelPro vise à simplifier la gestion des voyages d'affaires, permettant aux professionnels d’optimiser leur temps et de se concentrer sur l’essentiel.

---

## 🧭 Table des matières

[🎯 Fonctionnalités](#-fonctionnalités)  
[✅ Roadmap](#-roadmap)  
[💻 Prérequis](#-prérequis)  
[🚀 Installation](#-installation)  
[☕ Utilisation](#-utilisation)  
[🔗 APIs utilisées](#-apis-utilisées)  
[👥 Membres du Projet](#-membres-du-projet)  
[😄 Contribution](#-contribution)  
[📄 Licence](#-licence)

---

## 🎯 Fonctionnalités

- **Gestion des déplacements professionnels** : Planification et gestion des itinéraires, réservations de vols et hôtels.
- **Gestion des avis** : Permet aux utilisateurs de laisser des avis sur les hôtels, les vols, et les services utilisés.
- **Gestion financière** : Suivi des dépenses de voyage, gestion des paiements en ligne avec Stripe.
- **Réservation** : Réservation de vols, hôtels, et services de transport.
- **Gestion des événements** : Planification et gestion d'événements d'entreprise, y compris les conférences et séminaires.
- **Gestion de la boutique** : Boutique en ligne pour les produits liés au voyage, y compris les accessoires et les équipements professionnels.
- **Notifications en temps réel** : Alertes sur les mises à jour des vols, des hôtels et des événements.
- **Exportation PDF** : Détails des réservations et itinéraires en format PDF.
- **Système de notation** : Notation des hôtels, vols, et événements pour aider les utilisateurs à faire des choix éclairés.
- **Filtre de contenu** : Protéger l'expérience utilisateur avec un filtre de contenu (profanity filter).
- **Traduction automatique** : Traduction des textes dans plusieurs langues pour les utilisateurs internationaux.
- **Carte interactive** : Affichage des cartes pour les itinéraires de voyage et les événements.

---

## ✅ Roadmap

- [x] Authentification Google & Captcha
- [x] Système de réservation de vols et hôtels
- [x] Gestion des avis et des évaluations
- [x] Suivi des finances et gestion des paiements avec Stripe
- [x] Filtre de contenu pour les avis (Profanity Filter)
- [ ] Notifications temps réel (à venir)
- [ ] Intégration API pour gestion des événements (à venir)
- [ ] Traduction automatique (à venir)
- [ ] Ajout d'un système de gestion des réservations d'événements (à venir)
- [ ] Carte interactive (à venir)

---

## 💻 Prérequis

Avant de commencer, assurez-vous d’avoir installé :

- [PHP ≥ 8.1](https://www.php.net/downloads)
- [Symfony CLI](https://symfony.com/download)
- [Composer](https://getcomposer.org/download/)
- [MySQL ≥ 5.7](https://dev.mysql.com/downloads/mysql/)
- [Node.js ≥ 16 (facultatif)](https://nodejs.org/)
- [Cloudinary API](https://cloudinary.com/) pour le stockage d’images.

---

## 🚀 Installation

1. Clonez le projet depuis GitHub :


git clone https://github.com/ismail-belhoula/TravelPro_Symfony.git
cd travelpro

### Détails des nouvelles APIs :

- **Profonity Filter** : Utilisé pour filtrer les mots inappropriés dans les commentaires et avis des utilisateurs.
- **Stripe** : Gère les paiements en ligne pour les réservations de voyages et événements.
- **Mailing** : Envoi des notifications et des confirmations par email à l'aide de services comme Mailgun.
- **PDF Generation** : Utilisation de bibliothèques comme DomPDF pour générer des documents PDF pour les réservations et les itinéraires.
- **Cloudinary** : Service de stockage cloud pour héberger et gérer les images liées aux utilisateurs (par exemple, photos de profil).
- **Traduction** : Utilisation de l'API DeepL ou Google Translate pour la traduction automatique des contenus dans plusieurs langues.
- **Map API** : Intégration des cartes interactives pour afficher les itinéraires de voyage et les événements.

Cela enrichit la plateforme **TravelPro** pour offrir une expérience utilisateur encore plus fluide et professionnelle.
