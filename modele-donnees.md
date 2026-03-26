# Modèle de données

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