# Routes de l'application

| URL | Méthode HTTP | Contrôleur       | Méthode | Titre HTML           | Commentaire    |
| --- | ------------ | ---------------- | ------- | -------------------- | -------------- |
| `/` | `GET`        | `MainController` | `home`  | Bienvenue sur O'flix | Page d'accueil |
| `/show/{id}` | `GET`        | `MainController` | `show`  | O'flix - $titre | Page des détails d'un film ou d'une série |
| `/list` | `GET`        | `MainController` | `list`  | O'flix | Liste de film ou série |
| `/favorites` | `GET`        | `MainController` | `favotites`  | O'flix - Mes favories| Page des favories d'un utilisateur |
| `/api/movies` | `GET`        | `ApiController` | `list`  | - | liste des movies |
| `/api/movies/{id}` | `GET`        | `ApiController` | `movies_get`  | - | toutes les informations d'un movie |
| `/theme/toggle` | `GET`        | `MainController` | `themeSwitcher`  | - | changement de théme |