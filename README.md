# Test technique - Maison Despriet

Ce repo contient une application Laravel 11 qui permet à des clients d'ajouter des produits à leur panier. Le système existant enregistre les commandes dans la base de données (MySQL), calcule le prix de chaque item dans le panier, et renvoie au front-end le détail du prix total de la commande.

Votre but est de créer un système de remises et de marges applicables à ces commandes. Ces remises/marges doivent provenir de la base de données, via un modèle, pour les rendre dynamiques (le pourcentage, notamment).

Il n'y a pas de restrictions sur les outils et documentations à votre disposition, et pas de limite de temps. Quand vous avez terminé, ouvrez une pull-request sur ce repo. Dans la description, expliquez votre approche en quelques lignes.

## Cas à couvrir :

- Clients normaux : pas de remise ni marge globale de base ;
- Clients VIP : remise globale de 10% non-cumulable sur le prix de vente (en DB : `products -> price_selling`) de tous les articles ;
- Clients grossistes : marge globale de 30% sur le prix d'achat (en DB : `products -> price_acquisition`) de tous les articles ;
- Catégorie de produits "surgelés" : remise de 5% sur les prix de vente des articles concernés qui ne s'applique pas aux grossistes ;
- Catégorie de produits "promotions" : remise de 15% sur les prix de vente des articles concernés qui ne s'applique pas aux grossistes.

## Consignes :

#### Back-end

Analyser le système existant et y intégrer l'application des remises / marges. 
Lorsque plusieurs remises peuvent s'appliquer pour un même produit, c'est uniquement la plus avantageuse qui est reprise.

#### Front-end

Adapter le composant `resources/vue/pages/cart.vue` pour afficher le montant total des remises, en une seule ligne, en dernière position juste avant le total de la commande, sous forme "Remises : -X €". Attention, en cas de marge, cette ligne ne doit pas apparaître, car la marge doit être intégrée directement dans le sous-total de la commande.

#### Tests
Écrire des tests pour garantir la fonctionnalité d'application des remises/marges : utilisez phpunit ou pest selon vos préférences et testez au minimum tous les cas à couvrir listés ci-dessus en analysant les résultats possibles de requêtes POST sur l’URL d’ajout au panier.

## Glossaire 

- Remise : Une action qui diminue le prix d'un produit d'un certain pourcentage
- Marge : Une action qui augmente le prix d'un produit d'un certain pourcentage
- Grossiste : Type de client pour lequel le prix final est calculé sur base du prix d'achat + marge au lieu de prix de vente - remise. `Wholesaler` dans le code.
