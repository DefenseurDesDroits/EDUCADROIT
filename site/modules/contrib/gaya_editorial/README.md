# Module Gaya Éditorial

## Informations générales

Ce module ajoute automatiquement plusieurs champs pour Drupal 8 dont les caractéristiques sont les suivantes :

- ```field_ge_editorial```
    - Ce champ fournit un field permettant d'ajouter différents types de blocs de contenus éditoriaux comme une image, une vidéo, du texte, un titre de sommaire. Celui-ci, est ajouté automatiquement et disponible sur les entités de type ```noeud```.
- ```field_ge_module```
    - Ce champ fournit un field permettant d'ajouter des modules customs (Appel depuis modules, fonction perso). Un hook a été mis en place afin de pouvoir ajouter des entrées directement et simplement dans le select de ce champ. Celui-ci, est ajouté automatiquement et disponible sur les entités de type ```noeud``` et ```gaya_editorial_type```.

- Ces deux modules fonctionnent en étroite collaboration avec le thème ```gaya```. Ainsi, le thème de votre projet, doit avoir en **"base theme"** : ```gaya``` ([voir le repository git du thème gaya](http://git.gaya.fr/projects/GAYA/repos/drupal-8-theme-gaya))

  

## Versions
- ```8.x-beta```
    - Release date : **01/02/2016**
    - Cette version est la première et mets en place le socle de développement de ce module et des sous
  

## Currently in dev
- [x] Picture
- [x] Index (Sommaire de contenu)
- [] Vidéo (Multiple provider)
- [] Slideshow
- [] Texte Filtré (RTE) 

  

## Installation
Pour installer ce module, il faut simplement aller sur la page des modules, et activer "Gaya Editorial".

Il faut ensuite se rendre sur la page :
**"/admin/config/gaya/editorial"** et activer les différents types de blocs éditoriaux dont vous avez besoin pour le projet.

**[NOTE]** : L'activation ou la désactivation de ces sous-modules, ne supprimes-en rien les contenus déjà existants, mais ils ne seront plus affiché sur le front.

  

## Informations techniques
Chaque module défini un certain nombre de templates twig pouvant être surchargé en plaçant les fichiers twig dont vous avez besoin, dans votre thème courant.

  

## Champ : field\_ge\_module
Ce champ est de type multiple et fourni un **"form\_element"** et de type **"select"** vers des fonctions custom. Pour pouvoir ajouter des options dans ce select, il faut un hook est disponible : ```HOOK_ge_module_api``` et doit retourner un : ```array([fonction] => [title])```

  

## Champ : field\_ge\_editorial
Ce champ est de type multiple et fourni un **"form\_element"** et de type **"entity\_reference"** de view form mode **"inline\_entity\_form"** et de view render **"entity_render"** vers des fonctions custom. La liste des possibilités est gérée par les différents sous-modules présents dans le module **"gaya\_editorial"**.

  

## Sous module : gaya\_editorial\_picture
Ce module permet de disposer dans la liste des blocs éditoriaux d'un champ de type **"image"**, avec différentes configurations possibles :

- Titre (BO uniquemement),
- Position,
- Style à appliquer,
- Image (avec Alt, Title)

  

## Sous module : gaya\_editorial\_index
Ce module permet de disposer dans la liste des blocs éditoriaux d'un champ de type **"textfield"**, avec les champs suivants :

- Titre (BO uniquemement),
- Titre dans le sommaire

Ce module permet de disposer dans l'objet **"node"**, d'une liste de titre permettant de créer un **"sommaire"**.

