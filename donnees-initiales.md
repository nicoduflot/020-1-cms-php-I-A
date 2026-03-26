# Données initiales

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