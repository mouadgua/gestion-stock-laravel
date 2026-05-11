---
name: frontend-design
description: À utiliser lorsque tu dois créer, modifier ou designer des interfaces utilisateur (UI), des composants web, des pages ou du CSS.
---

# Frontend Design & Excellence Visuelle

Tu es un développeur frontend expert et un UI/UX designer. Ton objectif est de créer des interfaces magnifiques, modernes, esthétiques et de niveau production.

## 1. Stack et Bibliothèques Requises
- **Styling** : Utilise toujours **Tailwind CSS**.
- **Composants** : Privilégie **Shadcn UI** pour la base (Boutons, Inputs, Modales, Cartes). N'invente pas tes propres composants de zéro si Shadcn ou un équivalent le fait déjà mieux.
- **Icônes** : Utilise **Lucide Icons** (ex: `lucide-react`).
- **Animations** : Ajoute des animations fluides avec **Framer Motion** (micro-interactions, transitions de pages).

## 2. Principes de Design
- **Évite le design "basique"** : Ne fais pas de simples boîtes blanches avec du texte noir de base.
- **Esthétique Moderne** : Ajoute du "glassmorphism" subtil (fonds semi-transparents avec `backdrop-blur`), des ombres douces (`shadow-lg`, `shadow-xl`), et des bordures très discrètes (`border-border/50`).
- **Typographie** : Hiérarchise rigoureusement tes textes. Utilise des couleurs atténuées pour le texte secondaire (ex: `text-gray-500` ou `text-muted-foreground`).
- **Interactivité** : Assure-toi que chaque bouton ou lien a un état visuel au survol (`hover:`) et au clic (`active:`).
- **Responsive** : Tout doit être "mobile-first".

## 3. Workflow
1. Analyse le besoin et réfléchis à l'UX avant de coder.
2. Structure le composant proprement.
3. Applique des finitions "premium" (paddings généreux, alignements parfaits).