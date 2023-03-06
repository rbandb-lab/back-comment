# Objectif [![](https://raw.githubusercontent.com/aregtech/areg-sdk/master/docs/img/pin.svg)](#roadmap)

Créer un service web de discussion et de commentaires d'artciles

## Fonctionnalités:

### Backend :

• Consulter la liste des commentaires et les réponses aux commentaires\
• Poster un commentaire\
• Poster une réponse a un commentaire\
• Créer un système de notation des commentaire\
• Mettre en place un système d’autorisation sur les API

### Infra / Frontend :

• Pouvoir instancier le service de commentaires sur n’importe quelle page\
• Sécuriser le formulaire de post de commentaires contre les robots\
• S’authentifier via Facebook et/ou Google

### Frontend:
- Le choix a été fait de faire un frontend qui soit une application Symfony pour passer moins de temps
mais il est tout à fait faisable de mettre en place la solution qui vous convient le mieux. Un IDP, connexion
oAuth2 ou Oidc-connect via un petit service de SSO.

### Notes:

- La branche hexagone contient le model isolé de manière à construire une [architecture hexagonale](https://fr.wikipedia.org/wiki/Architecture_hexagonale)
- La branch CQRS implémente le pattern [CQRS](https://fr.wikipedia.org/wiki/S%C3%A9paration_commande-requ%C3%AAte) de séparation
des commandes et requêtes

Dans le cadre d'un service dédié, ces choix techniques spécifiques sont motivés par le souhait
d'isoler le métier et de privilégier un code plus simple facile à maintenir dans le temps.
De plus la testabilité des règles business est améliorée.

- La branche API-P [WIP] va contenir une "translation" de cet hexagone vers API platform