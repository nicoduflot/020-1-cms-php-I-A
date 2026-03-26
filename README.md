# TD CMS PHP - MySql

## Objectif

Créer en une semaine un petit CMS de type blog avec les fonctionnalités principales suivante : 

* créer du contenu
* lire le contenu
* connexion utilisateur

### Le contenu

Un seul type de contenu, le post, constitué d'un titre et du corps, enregistré en HTML, implémenté grâce à du WYSIWYG

### Les utilisateurs

Les comptes seront créés uniquement par l'administrateur

De base, un visiteur non connecté n'accède qu'au contenu publié public

**Différents rôles sur différents droits**
|Rôle | Droit(s)|
|---|---|
|Admin (avec compte) |Gère tout, accède à tout : contenu, utilisateurs|
|Webmaster (animateur site) (avec compte)| accède à tout, ajout, suppression du contenu|
|Abonné (avec compte) |Compte abonné, accès au contenu privé, accès au contenu public|
|Visiteur |(sans compte) Accès au contenu public|

### Matrice des droits à implémenter

|Droit | Admin |Webmaster |Abonné |Visiteur|
|---|---|---|---|---|
|lire_public |✅| ✅ |✅ |✅|
| lire_prive |✅| ✅ |✅ |❌|
|creer_post |✅ |✅ |❌ |❌ |
|modifier_post |✅ |✅ |❌ |❌ |
|supprimer_post |✅ |✅ |❌ |❌ |
|gerer_utilisateurs |✅ |❌ |❌ |❌ |

## Le modèle de données

[Modèle de données](./modele-donnees.md)

### Création de la base

[Modèle sql](./sql-bdd.md)

### Données initiales de la base.

[Données initiales](./donnees-initiales.md)

