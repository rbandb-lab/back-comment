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

### Rappel sur l'architecture Hexagonale ![Hexa](./docs/hexa.png)

- Un layer ne peut communiquer qu'avec son propre code et avec celui des couches inférieures
- L'Infra a connaissance de l'application
- L'application a connaissance du domaine (mais pas de l'infra)
- Le domaine n'a de connaissance que de lui-même (y compris ports et adapters matérialisés
par des interfaces)

- Dans les découpages les plus simples, on peut avoir simplement un domaine et une infrastructure
- Dans notre repo, nous avon ajouté des notions:
- UI, qui est une sous-partie de l'infra
- SharedKernel qui regroupe des interfaces communes au domaine (et sous-domaines)

La branche API-P va contenir une "translation" de cet hexagone vers API platform

## Api platform:
Api platform v3 est un framework qui expose des **Resources** via une Api et qui offre
des integrations avec des DataProvider et DataProcessor. 

Dans sa config par defaut, on a une resource = une entity = une table et le CRUD opère automatiquement
de l'UI vers la BDD et de la BDD vers l'UI

### Utiliser un domaine riche:
- l'Hexagone par définition fonctionne avec n'importe quels ports et adapters
- Il est possible d'utiliser API-P pour interagir avec le model en lui déléguant ces ports
- En particulier le pattern CQRS est pris en charge et facilite les interactions

### CQRS:
- La proposition des Tilleuls est d'implémenter CQRS de la manière suivante ![CQRS schema](./docs/command_query.png)
- On constate que de ce point de vue :
1. L'Api Resource joue le role d'InputDTO dans une Command et d'OutputDTO dans une Query, ou plutôt
c'est un DTO sur lequel on branche les couches ORM et HTTP
2. les dépendences du MVC sont donc inversées et permettent d'intercaler le Domain dans le cycle Request->Response
3. En théorie, la représentation persistée, la représentation de la View et la resource exposée devraient être 3 
représentations distinctes.

On voit donc que l'implémentation DDD-CQRS via API-P propose une résolution "clé en main" dès lors que l'API-Resource
joue les 3 roles (DTO, DataMapper, ViewModel). Et cela fait sens, car cela induit une **réduction de la charge de travail**
par rapport à un modèle orthodoxe. API-P fonctionne aussi moins bien si on accepte pas ce deal.

Le framework fournit des mécanismes pour ajouter tout cela sur l'Api-Resource:
1. customization des vues via les normalizers, events, etc
2. utilisation des ValueObjects via les embeddables doctrine
3. intégration des validations

Pour la branche API-P il faut donc :
- Ajouter les Api Resources (Infra), mapping et groupes (validation et serialization) sur les Api Resource

### Author / User:
- Un User est une structure de data fournies par l'infra et la couche de sécurité
- Dans notre domaine, c'est un ValueObject "Author"
- Afin de pouvoir tester, nous faisons apparaitre un "User" en attente d'un fournisseur de "User" (IDP),
qui peut-être fourni via credentials, JWS, etc

