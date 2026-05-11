Cahier des Charges : Gestion d’un Magasin Virtuel
1. Introduction
Le projet consiste à développer un système de gestion pour un magasin virtuel. Ce système
permettra de gérer les produits, les clients, et les commandes de manière efficace et
sécurisée. Il intègre des fonctionnalités avancées comme le calcul automatique des totaux, la
gestion des stocks, et la vérification des données avant insertion ou modification.
L’application inclut deux espaces distincts :
• Espace Administrateur : Gestion complète du magasin et supervision des opérations.
• Espace Client : Interface permettant de consulter, sélectionner des produits et passer
des commandes.
2. Objectifs
• Faciliter la gestion des produits, clients, et commandes.
• Séparer clairement les rôles et les droits d’accès entre clients et administrateurs.
• Garantir l’intégrité des données à travers l’utilisation de déclencheurs et de
procédures stockées.
• Offrir une interface utilisateur intuitive pour les deux espaces.
3. Fonctionnalités
Partie Client :
1. Gestion de Compte :
• Inscription avec email, mot de passe, et informations personnelles (nom,
adresse, téléphone).
• Connexion sécurisée avec validation des identifiants.
• Mise à jour des informations personnelles.
• Consultation de l’historique des commandes.
2. Navigation et Recherche de Produits :
• Consultation des produits par catégories.
CITE DES METIERS ET DES COMPÉTENCES – Région RABAT-SALÉ- KÉNITRA
• Recherche avancée (nom, prix, disponibilité).
• Affichage des détails d’un produit (image, description, prix, stock disponible).
3. Panier d’Achat et Commandes :
• Ajout d’articles au panier.
• Modification du panier (quantité, suppression).
• Validation de la commande avec calcul automatique du total.
• Paiement sécurisé (simulation).
4. Liste de Souhaits :
• Sauvegarde des produits préférés pour consultation future.
Partie Administrateur :
1. Gestion des Produits :
• Ajout, modification et suppression des produits.
• Suivi du stock en temps réel.
• Gestion des catégories (ajout, modification, suppression).
2. Gestion des Clients :
• Consultation de la liste des clients inscrits.
• Modification ou suppression des comptes en cas de problème.
3. Gestion des Commandes :
• Supervision des commandes passées.
• Mise à jour du statut des commandes (en attente, expédiée, livrée).
4. Statistiques et Rapports :
• Génération de rapports sur les ventes (produits les plus vendus, chiffre
d’affaires).
• Consultation des stocks pour éviter les ruptures.
5. Sécurité et Gestion des Droits :
• Accès réservé et sécurisé pour l’espace administrateur.
• Gestion des autorisations pour protéger les données sensibles.
CITE DES METIERS ET DES COMPÉTENCES – Région RABAT-SALÉ- KÉNITRA
4. Structure « Minimal » de la Base de Données
a) Tables
• Produits
• id_produit (Primary Key)
• nom_produit (VARCHAR)
• prix (FLOAT)
• stock (INTEGER)
• Clients
• id_client (Primary Key)
• nom_client (VARCHAR)
• email (VARCHAR UNIQUE)
• adresse (TEXT)
• Commandes
• id_commande (Primary Key)
• id_client (Foreign Key)
• date_commande (DATE)
• total (FLOAT)
• Ligne_Commandes
• id_ligne (Primary Key)
• id_commande (Foreign Key)
• id_produit (Foreign Key)
• quantite (INTEGER)
• sous_total (FLOAT)
5. Règles de Gestion
• Produits :
• Le stock doit toujours être positif ou nul.
• Si le stock est insuffisant pour une commande, l’opération doit échouer.
• Clients :
CITE DES METIERS ET DES COMPÉTENCES – Région RABAT-SALÉ- KÉNITRA
• L’adresse email doit être unique.
• Les clients doivent avoir une adresse valide.
• Commandes :
• Une commande doit contenir au moins un produit.
• Le total de la commande est calculé automatiquement après l’ajout des
produits.
6. Exemple de Flux
1. Ajout d’un Produit :
• L’administrateur saisit les informations du produit (nom, prix, stock).
• Déclencheur : Vérifie que le stock est >= 0.
2. Création d’une Commande :
• Un client sélectionne les produits et leurs quantités.
• Déclencheur : Vérifie la disponibilité des stocks.
3. Validation de la Commande :
• Le total est calculé automatiquement.
• Déclencheur : Réduit le stock des produits commandés.
4. Modification d’une Commande :
• Si un produit est retiré ou sa quantité réduite, le stock est ajusté.