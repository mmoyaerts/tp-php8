# Rendus MVC

## Twig : définition d'une extension pour créer des URL dynamiquement

- Création d'une fonction Twig `path` à laquelle on passerait le nom d'une route, et qui nous renverrait l'URL correspondante

## Paramètres d'URL

- Pour gérer des URL comme `/products/5` ou bien `/products/mon-produit`, donc avec des parties **variables**
- Définir un composant applicatif capable d'identifier et d'extraire un paramètre d'URL pour ensuite pouvoir l'injecter au sein d'un contrôleur
- Pour une route donnée, si on a une URL type `/products/{id}` par exemple, alors faire en sorte que `/products/5` sache reconnaître le valeur 5 et la fournir en tant que paramètre du contrôleur
- Un contrôleur ressemblerait donc à un truc comme ça :

```php
#[Route('/products/{id}', 'product_item', 'GET')]
public function item(ProductRepository $repo, int $id)
{
    $product = $repo->find($id);
    //...
}
```

## Instanciation dynamique de services

- Aujourd'hui, notre container contient des instances déjà créées de services applicatifs
- Il pourrait être intéressant de changer ce fonctionnement pour qu'un service soit créé dynamiquement lorsqu'on en a besoin

## Réalisation d'un service d'upload de fichiers

- Depuis un contrôleur réceptionnant les données d'un formulaire, création et utilisation d'un service d'upload de fichiers
- Le but serait de consommer une instance de classe à laquelle on passerait, par exemple, un dossier de destination, un fichier entrant, etc...
- On pourrait alors définir des contraintes de validation (type du fichier, taille maximale, etc...) et renvoyer une erreur en fonction de la situation

## Installation et utilisation de symfony/http-foundation

- Le composant `symfony/http-foundation` définit deux classes majeures `Request` et `Response`, qui pourraient être utilisées au sein du MVC
- La classe `Request` contient également une méthode statique `createFromGlobals` permettant de construire un objet `Request` contenant déjà les données `GET`, `POST`, `FILES`, etc...
- On pourrait donc faire utilisation de ce composant pour améliorer la structure du MVC

## Définition de commandes dans la console

- Intégration du composant `symfony/console` pour créer des commandes accessibles depuis le terminal
- L'idée est de pouvoir définir des commandes personnalisées, comme par exemple :
  - Envoyer un email de test
  - Créer un utilisateur administrateur avec son mot de passe déjà haché
  - Créer un ensemble de données de tests (fixtures), avec des données fakes (on pourra installer un composant type `fakerphp/faker`)

## Définition d'une suite de tests

- Installation de PHPUnit puis écriture de tests unitaires pour des classes comme `App\Routing\Router`, `App\DependencyInjection\Container`, etc...

## Authentification/Autorisation

- Authentification au sein de l'application (user/mot de passe) via un formulaire de login donc un contrôleur avec la méthode POST
- Gestion de la session en cas d'authentification réussie : écriture d'un service de gestion de session qui permettrait, via un objet, d'interagir avec le tableau superglobal `$_SESSION`
- Pour les autorisations : écriture d'un attribut applicable au-dessus d'un contrôleur, qui permettrait d'indiquer si on doit être connecté ou non pour accéder au contrôleur
- Note : pas besoin de faire des rôles

## Refactorisation du routeur & de la configuration

- Point de départ : Le routeur fait trop de choses
- Création d'une classe permettant de piloter et organiser le routeur et l'injection des services
- On pourrait imaginer une classe _principale_ type `App` ou `Kernel` qui regrouperait le routeur, pour la fourniture des routes, et le container de services, pour la fourniture des services
- On pourrait donc regrouper l'initialisation et la configuration de services dans cette classe, puis l'exécution du routeur avec le container, etc...dans une méthode type `handle` ou `dispatch`...
