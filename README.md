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

|entité role|
|---|
|__id__, int PK<br />nom, varchar 50. ex: 'admin', 'webmaster', 'abonne', 'visiteur'<br />libelle, varchar 100. ex: 'Administrateur', 'Webmaster'|

|entité droit|
|---|
|__id__, int PK<br />nom, varchar 100. ex: 'lire_public', 'lire_prive', 'creer_post', 'modifier_post', 'supprimer_post', 'gerer_utilisateurs' |

|entité role_droit  (table de liaison)|
|---|
|__role_id__,  int PK FK → role.id<br />__droit_id__, int PK FK → droit.id|

|entité utilisateur|
|---|
|__id__, int PK<br />role_id, int FK → role.id   ← remplace le varchar 'role'<br />nom, varchar 255<br />prenom, varchar 255<br />email, varchar 255<br />login, varchar 255<br />motdepasse,varchar 255|

|entité post (le contenu)|
|---|
|__id__, int PK <br />utilisateur_id,  int FK <br />titre, varchar 255 <br />slug, varchar 191 <br />body, text <br />publie, boolean <br />cree, datetime <br />modifie, datetime|

|entité tag|
|---|
|id, int PK<br />nom, varchar 100|

|entité post_tag|
|---|
|__post_id__, PK FK  <br />__tag_id__, PK FK|

### Création de la base

```sql
-- ========================================
-- BASE CMS DEMO - Formation PHP initiation
-- ========================================

-- 1. ROLES
CREATE TABLE role (
    id      INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nom     VARCHAR(50)  NOT NULL,
    libelle VARCHAR(100) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_role_nom (nom)
);

-- 2. DROITS
CREATE TABLE droit (
    id  INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_droit_nom (nom)
);

-- 3. LIAISON ROLE <-> DROIT
CREATE TABLE role_droit (
    role_id  INT UNSIGNED NOT NULL,
    droit_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (role_id, droit_id),
    CONSTRAINT fk_rd_role  FOREIGN KEY (role_id)  REFERENCES role(id)  ON DELETE CASCADE,
    CONSTRAINT fk_rd_droit FOREIGN KEY (droit_id) REFERENCES droit(id) ON DELETE CASCADE
);

-- 4. UTILISATEURS
CREATE TABLE utilisateur (
    id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    role_id    INT UNSIGNED NOT NULL,
    nom        VARCHAR(255) NOT NULL,
    prenom     VARCHAR(255) NOT NULL,
    email      VARCHAR(191) NOT NULL,
    login      VARCHAR(191) NOT NULL,
    motdepasse VARCHAR(255) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_utilisateur_email (email),
    UNIQUE KEY uq_utilisateur_login (login),
    CONSTRAINT fk_utilisateur_role FOREIGN KEY (role_id) REFERENCES role(id)
);

-- 5. POSTS
CREATE TABLE post (
    id       INT UNSIGNED NOT NULL AUTO_INCREMENT,
    utilisateur_id  INT UNSIGNED NOT NULL,
    titre    VARCHAR(255) NOT NULL,
    slug     VARCHAR(191) NOT NULL,
    body     TEXT         NOT NULL,
    publie   TINYINT(1)   NOT NULL DEFAULT 0,
    cree     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    modifie  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_post_slug (slug),
    CONSTRAINT fk_post_utilisateur FOREIGN KEY (utilisateur_id) REFERENCES utilisateur(id)
);

-- 6. TAGS
CREATE TABLE tag (
    id  INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_tag_nom (nom)
);

-- 7. LIAISON POST <-> TAG
CREATE TABLE post_tag (
    post_id INT UNSIGNED NOT NULL,
    tag_id  INT UNSIGNED NOT NULL,
    PRIMARY KEY (post_id, tag_id),
    CONSTRAINT fk_pt_post FOREIGN KEY (post_id) REFERENCES post(id) ON DELETE CASCADE,
    CONSTRAINT fk_pt_tag  FOREIGN KEY (tag_id)  REFERENCES tag(id)  ON DELETE CASCADE
);
```

### DOnnées initiales de la base.

```sql
-- ========================================
-- DONNEES INITIALES
-- ========================================

-- Rôles
INSERT INTO role (nom, libelle) VALUES
    ('admin',      'Administrateur'),
    ('webmaster',  'Webmaster'),
    ('abonne',     'Abonné'),
    ('visiteur',   'Visiteur');

-- Droits
INSERT INTO droit (nom) VALUES
    ('lire_public'),
    ('lire_prive'),
    ('creer_post'),
    ('modifier_post'),
    ('supprimer_post'),
    ('gerer_utilisateurs');

-- Matrice rôles / droits
-- Admin : tous les droits
INSERT INTO role_droit (role_id, droit_id)
SELECT r.id, d.id FROM role r, droit d
WHERE r.nom = 'admin';

-- Webmaster : tout sauf gerer_utilisateurs
INSERT INTO role_droit (role_id, droit_id)
SELECT r.id, d.id FROM role r, droit d
WHERE r.nom = 'webmaster'
  AND d.nom IN ('lire_public', 'lire_prive', 'creer_post', 'modifier_post', 'supprimer_post');

-- Abonné : lecture seulement
INSERT INTO role_droit (role_id, droit_id)
SELECT r.id, d.id FROM role r, droit d
WHERE r.nom = 'abonne'
  AND d.nom IN ('lire_public', 'lire_prive');

-- Visiteur : contenu public uniquement
INSERT INTO role_droit (role_id, droit_id)
SELECT r.id, d.id FROM role r, droit d
WHERE r.nom = 'visiteur'
  AND d.nom = 'lire_public';
```

**ce que permet cette structure en particulier**

* ```ON DELETE CASCADE``` sur les tables de liaison - si on supprime un post, ses entrées dans post_tag disparaissent automatiquement. C'est plus propre que de devoir gérer ça manuellement côté PHP.
* ```TINYINT(1)``` pour le booléen - MySQL n'a pas de type ```BOOLEAN``` natif, il l'alias vers ```TINYINT(1)```. Valeur 0 = non publié, 1 = publié. Ça mérite d'être expliqué aux stagiaires.
* ```DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP``` sur modifie - le champ se met à jour automatiquement à chaque modification de la ligne, sans avoir à le gérer en PHP.
* ```UNIQUE KEY``` sur login, slug, et nom tag - évite les doublons au niveau base de données, en complément des vérifications PHP. La défense en profondeur à deux niveaux est une bonne habitude à montrer dès le début.
* ```UNIQUE KEY``` - l'email est souvent utilisé comme identifiant alternatif au login, donc contrainte d'unicité recommandée. La taille ```VARCHAR(191)``` est le choix standard. La RFC 5321 autorise théoriquement jusqu'à 254 caractères pour une adresse email. 
    >En pratique, on utilise VARCHAR(191) sur les champs indexés en utf8mb4 malgré la recommandation RFC >de 254 caractères, pour respecter la limite d'index MySQL.
    * Stocker en minuscules — côté PHP, pensez à normaliser avant insertion et recherche.

L'ordre de création est important — si on crée les tables dans le désordre, MySQL refusera les FK. il existe des dépendances entre les tables.

Ne jamais stocker en clair le mot de passe - sur la table utilisateur, motdepasse doit contenir un hash password_hash() et non le mot de passe brut. Le champ ```VARCHAR(255)``` est d'ailleurs la taille recommandée pour accueillir la sortie de password_hash() (bcrypt produit 60 caractères, mais argon2 peut être plus long selon la config).

Pour ```password_hash()```, l'utilisation de ```PASSWORD_DEFAULT``` est justement conçue pour suivre les recommandations de sécurité au fil des versions de PHP, donc le hash évolue automatiquement sans changer le code.

```PASSWORD_DEFAULT``` utilise bcrypt aujourd'hui, mais si PHP bascule vers argon2 dans une future version, les anciens hashs restent valides car PHP stocke l'algorithme utilisé dans la chaîne elle-même. C'est justement pourquoi ```VARCHAR(255)``` est important — ne pas brider la longueur.

```password_needs_rehash()``` permet de ne jamais avoir à forcer une réinitialisation des mots de passe lors d'une montée de version PHP. Un bon exemple concret du principe "sécurité sans friction utilisateur".

**Limitation des clefs uniques à 191 caractères**

En ```utf8mb4``` (le charset moderne qui supporte les emojis), chaque caractère peut prendre jusqu'à 4 octets, donc ```VARCHAR(255)``` occupe potentiellement 1020 octets dans un index — ce qui dépasse la limite de 1000 octets de MyISAM, ou d'InnoDB avec certaines configurations.

191 caractères × 4 octets = 764 octets, bien en dessous de la limite. C'est d'ailleurs pourquoi vous aviez mis ```VARCHAR(191)``` sur le slug de post - même raison. C'est pourquoi le ```VARCHAR(191)``` apparaît souvent dans les schémas MySQL/MariaDB sur les champs indexés.