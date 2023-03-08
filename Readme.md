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
- Dans notre repo, nous avons ajouté des notions:
- UI, qui est une sous-partie de l'infra
- SharedKernel qui regroupe des interfaces communes au domaine (et sous-domaines)

La branche API-P va contenir une "translation" de cet hexagone vers API platform

## Api platform:
Api platform v3 est un framework qui expose des **Resources** via une Api et qui offre
des intégrations avec des DataProvider et DataProcessor. 

Dans sa config par défaut, on a une resource = une entity = une table et le CRUD opère automatiquement
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
joue plusieurs des 3 roles (DTO, DataMapper, ViewModel). 

API-P est généralement utilisé pour son intégration avec Doctrine et parce qu'il propose une **réduction de la charge de travail**
par rapport à un modèle orthodoxe mais API-P fonctionne aussi moins bien si on n'accepte pas ce deal.

### Quelles sont donc les options ?

Le framework fournit des mécanismes pour ajouter tout cela sur l'Api-Resource:
1. customization des vues via les normalizers, events, etc
2. utilisation des ValueObjects via les embeddables doctrine
3. intégration des validations

API-P fonctionne au mieux quand on utilise les API Resource comme DTO et les models (au sens DDD) en tant
qu'entities, ce qui continue d'alimenter la confusion entre représentation du domaine et représentation dans une table
et surtout engendre un non-sens = le "model" inclut la base de données dans ses namespaces ...

Les "avantages" d'API-P, a savoir le couplage avec l'ORM et la couche HTTP, d'où découle l'API-doc auto + Hateoas,
commencent à dysfonctionner dès lors qu'on expose un model qui contienne des value objects, des aggregate, des url
du type "/article/{id}/price" car les automatisations ne sont pas prévues pour. Le paramétrage à effectuer (par ex
personalisation de l'API-DOC) devient alors lourd et plus fastidieux que de travailler from scratch.

Donc, quelques choix possibles si on veut faire du DDD + Hexagonal + CQRS **dans API-P** :
1. Accepter que le Model mappé sur la BDD pour réduire la charge de travail -> le bus command recoit un inputDTO (ApiResource)
et dispatch une commande, on traite la logique métier dans le CommandHandler en travaillant l'entity juste avant de la persister.
Et dans le cas d'une Query, on récupère le model, travaille sur la ou les entités, et hydratons une view (ApiResource)
2. Créer les 3 représentations d'InputDTO, DataMapper et ViewModel et faire une a plusieurs ApiResources pour le même
  objet en fonction des UseCase [mimer une API from scratch]
3. Admettre qu'API-P est un framework pour exposer rapidement un CRUD et le limiter à cet usage pour bénéficier au max
de ses avantages.


### Pour la branche API-P il faut donc :
- Ajouter les Api Resources (Infra) et les utiliser comme des DTO
- Les bus de message vont donc permettre de booter le model, effectuer la logique métier
- Il faut ensuite créer les entités doctrine dans le cas d'une command (depuis le Model)
- Et créer des groupes de serialization sur les Api Resource dans le cas d'une query

### Author / User:
- Un User est une structure de data fournies par l'infra et la couche de sécurité
- Dans notre domaine, c'est un ValueObject "Author"
- Afin de pouvoir tester, nous faisons apparaitre un "User" comme resource, en attente d'un fournisseur de "User" (IDP),
qui peut-être fourni via credentials, JWS, etc
