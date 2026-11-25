# Partie 3 — Connexion, déconnexion et sessions (WELLUP)

Périmètre (tableau n°3) : `login.php`, `requireAuth()`, configuration + expiration de la session (30 min),
`login()`, `findByEmail()`, `logout()`, `logoutIfValid()`, `isAuthenticated()`.
Correspond aux tâches GitHub n° 5 (Connexion), 6 (Déconnexion POST + CSRF) et 13 (Sécurité des sessions).

## 1. Contenu du ZIP

```
a_copier/app/
  session_config.php                      NOUVEAU fichier
  user_routes.php                         modifié (blocs « PARTIE 3 »)
  Controllers/AuthController.php          modifié (blocs « PARTIE 3 »)
  Views/utilisateurs/login.php            modifié (fichier entièrement à vous)
CHANGEMENTS.diff                          toutes les différences par rapport à la version du camarade
```

## 2. Comment coller dans le projet

Copier le dossier `a_copier/app/` sur le dossier `app/` du projet (accepter le remplacement).

* **Cas A — les autres membres n'ont pas encore modifié `AuthController.php` / `user_routes.php`** :
  remplacement direct des fichiers, rien d'autre à faire.
* **Cas B — d'autres membres ont déjà modifié ces deux fichiers (le plus probable avec Git)** :
  ne remplacez PAS le fichier entier. Ouvrez mon fichier et copiez uniquement les blocs entourés de
  `// ===== PARTIE 3 ... : DÉBUT =====` et `// ===== PARTIE 3 : FIN =====` à la place des blocs équivalents.
  `login.php` et `session_config.php` se copient toujours en entier.

Dans le `index.php` de l'équipe : si vous avez déjà des `ini_set('session....')` avant `session_start()`,
vous pouvez les laisser (aucun conflit) ou les supprimer, `session_config.php` les applique.

## 3. Ce qui a été ajouté / corrigé

| Élément | Avant | Maintenant |
|---|---|---|
| Expiration 30 min | Code inline dans les routes ; session détruite en silence, utilisateur perdu | `isSessionExpired()` + `touchSession()` ; redirection vers la connexion avec le message « Votre session a expiré » |
| Durée configurable | `1800` en dur | Constante `SESSION_LIFETIME` (`session_config.php`) |
| Cookie de session | Réglages seulement dans `index.php` de l'équipe | `HttpOnly`, `SameSite=Lax`, `use_strict_mode`, `use_only_cookies`, `Secure` si HTTPS |
| `login()` — énumération des comptes | `password_verify` non exécuté si e-mail inconnu (temps de réponse différent) | Hash factice : même message et même durée dans les deux cas |
| `login()` — force brute | Aucune limite | 5 échecs → blocage de 5 minutes |
| `login()` — validation | Seulement « non vide » | Format e-mail + longueur max aussi côté serveur |
| `login()` — après succès | ID de session régénéré | + jeton CSRF régénéré, compteurs d'échecs remis à zéro |
| `findByEmail()` | `SELECT *` | Colonnes explicites (requête préparée conservée) |
| `isAuthenticated()` | `!empty(user_id)` | `user_id` entier > 0 |
| `logout()` | Code dupliqué | Fonction statique `destroySession()` réutilisable (vide `$_SESSION`, supprime le cookie, détruit la session) |
| Route `logout` | Redirigeait toujours vers login | CSRF valide → `login&logged_out=1` (message) ; requête invalide → session conservée |
| Bouton « Précédent » | Pouvait réafficher une page protégée | En-têtes `Cache-Control: no-store` sur toutes les pages du module |
| Route `login` si déjà connecté | Toujours vers `dashboard` | Admin → `admin`, user → `dashboard` |
| `login.php` | — | E-mail conservé après une erreur (jamais le mot de passe), messages « session expirée » / « déconnecté », `maxlength`, `autofocus`, `role="alert"` |

## 4. Tests manuels (à refaire sur la machine de validation)

1. Mauvais mot de passe, puis e-mail inconnu → même message « E-mail ou mot de passe incorrect. »
2. Bon identifiant → user vers `?action=dashboard`, admin vers `?action=admin`.
3. DevTools → Application → Cookies : le cookie `PHPSESSID` a l'attribut **HttpOnly**.
4. Taper `index.php?action=logout` dans l'URL (GET) → rien ne se passe, vous restez connecté.
5. Bouton « Se déconnecter » → retour à la connexion avec le message ; puis « Précédent » du navigateur → la page protégée ne s'affiche pas.
6. Expiration : mettre `SESSION_LIFETIME` à `10` dans `session_config.php`, se connecter, attendre 11 s, recharger → page de connexion avec « Votre session a expiré ». **Remettre `1800` ensuite.**
7. 5 mots de passe faux de suite → « Trop de tentatives… » (même avec le bon mot de passe pendant 5 min).

Ces scénarios ont été exécutés sur une copie du projet (PHP 8.3, base SQLite à la place de MySQL) : tous conformes.
Non testé avec votre MySQL/XAMPP : la requête SQL est la même que celle du camarade, seules les colonnes sont nommées.

## 5. Git (pour la note 3.1 / 3.2)

```
git checkout -b feature/connexion-sessions
# copier les fichiers, puis :
git add app/session_config.php app/Views/utilisateurs/login.php
git commit -m "feat(auth): page de connexion + messages session expirée/déconnexion"
git add app/Controllers/AuthController.php
git commit -m "feat(auth): login sécurisé (anti-énumération, limitation des tentatives), destroySession"
git add app/user_routes.php
git commit -m "feat(session): expiration 30 min, cookie HttpOnly, no-store, route logout"
git push -u origin feature/connexion-sessions   # puis Pull Request vers main
```
Dans le GitHub Project Board, passer vos tâches 5, 6 et 13 à « Terminé » et les lier à la Pull Request.

## 6. Pour expliquer à l'oral (critère 1.4)

* **Pourquoi un message d'erreur unique ?** Ne pas révéler quels e-mails existent. Le hash factice supprime aussi la différence de temps.
* **Pourquoi `session_regenerate_id(true)` ?** Anti session fixation : un ID connu avant la connexion devient inutile.
* **Pourquoi la déconnexion en POST + CSRF ?** Un lien ou une image piégée (GET) ne doit pas pouvoir déconnecter l'utilisateur.
* **HttpOnly / SameSite ?** HttpOnly : JavaScript (XSS) ne peut pas lire le cookie ; SameSite : le cookie n'est pas envoyé lors de requêtes venant d'un autre site.
* **Expiration par inactivité** : `last_activity` est mis à jour à chaque requête ; au-delà de 30 min sans requête, la session est détruite.
* **Limite à connaître** : le blocage après 5 échecs est stocké dans la session ; un attaquant qui supprime son cookie le contourne. En production, on stockerait les tentatives en base (par e-mail ou IP).

## 7. Déclaration d'usage de l'IA (critère 4.1)

Ce travail a été produit avec l'aide d'un assistant IA à partir du code du camarade. Relisez chaque bloc, assurez-vous
de pouvoir l'expliquer, et mentionnez dans la déclaration du README de l'équipe ce que vous avez compris, vérifié et adapté.
