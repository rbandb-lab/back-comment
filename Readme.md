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

- La branche API-P devrait contenir une "translation" de cet hexagone vers API platform

### Méthodologie: (recommandée)

- Le BDD consiste à prototyper l'application en écrivant les attentes en langage humain.
Afin de réduire le temps de développement, on peut se contenter d'écrire des tests d'acceptance,
qui vont décrire les principaux comportements de l'application et éventuellement les cas d'erreurs.

- Sur les parties du code qui contiennent les règles business (certaines peuvent être déjà explicitées dans un ticket),
les tests unitaires doivent être appliqués. On ne teste pas une implémentation du code mais bien un comportement
métier [en plus une règle métier change moins souvent qu'une implémentation]. L'Hexagone permet de rendre facilement
testable, sans avoir à mocker des tas de services qui viennent de l'infra.

- E2E. Ces tests sont à faire le moins possible car de moins en moins utiles au fur et à mesure où l'on a bien fait les
deux premières séries de tests.

