# Modèle SQL

```sql
-- ========================================
-- BASE CMS DEMO - Formation PHP initiation
-- ========================================

-- 1. ROLES
DROP TABLE IF EXISTS `role`;
CREATE TABLE role (
    id      INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nom     VARCHAR(50)  NOT NULL,
    libelle VARCHAR(100) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_role_nom (nom)
);

-- 2. DROITS
DROP TABLE IF EXISTS `droit`;
CREATE TABLE droit (
    id  INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_droit_nom (nom)
);

-- 3. LIAISON ROLE <-> DROIT
DROP TABLE IF EXISTS `role_droit`;
CREATE TABLE role_droit (
    role_id  INT UNSIGNED NOT NULL,
    droit_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (role_id, droit_id),
    CONSTRAINT fk_rd_role  FOREIGN KEY (role_id)  REFERENCES role(id)  ON DELETE CASCADE,
    CONSTRAINT fk_rd_droit FOREIGN KEY (droit_id) REFERENCES droit(id) ON DELETE CASCADE
);

-- 4. UTILISATEURS
DROP TABLE IF EXISTS `utilisateur`;
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
DROP TABLE IF EXISTS `post`;
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
DROP TABLE IF EXISTS `tag`;
CREATE TABLE tag (
    id  INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_tag_nom (nom)
);

-- 7. LIAISON POST <-> TAG
DROP TABLE IF EXISTS `post_tag`;
CREATE TABLE post_tag (
    post_id INT UNSIGNED NOT NULL,
    tag_id  INT UNSIGNED NOT NULL,
    PRIMARY KEY (post_id, tag_id),
    CONSTRAINT fk_pt_post FOREIGN KEY (post_id) REFERENCES post(id) ON DELETE CASCADE,
    CONSTRAINT fk_pt_tag  FOREIGN KEY (tag_id)  REFERENCES tag(id)  ON DELETE CASCADE
);
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