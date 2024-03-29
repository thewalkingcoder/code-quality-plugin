# CODE QUALITY PLUGIN

[![Build Status](https://travis-ci.com/thewalkingcoder/code-quality-plugin.svg?branch=master)](https://travis-ci.com/thewalkingcoder/code-quality-plugin)

Plugin composer permettant la mise en place d'outils pour une  analyse qualité et respect des standards sur un projet symfony.

# Pré-requis

- git >=2.17
- symfony (website-skeleton) >=4.4 ou symfony >=4.4 (skeleton) avec le pack symfony/test-pack
- composer 2.x

# Installation

```

composer require twc/code-quality-plugin --dev

```

If you use composer v1.x try

```

composer require twc/code-quality-plugin:^1.0 --dev

```

Après l'installation plusieurs élements auront été créés.

| Type | Libelle     |   Def     |
|------|-------------|-----------|
| Dossier | quality | Permet de configurer les standards et règles qualités sur votre projet | 
| Fichier | Makefile | Ajoute des recettes  | 

# Utilisation

Une fois installé lors de vos commits les actions suivantes seront réalisées.

- Fix du code avec php cs fixer
- Fix du code avec php code sniffer
- Analyse statique du code avec phpstan

La moindre erreur arrête le process de commit pour que vous puissiez fixer le problème.

# Makefile

Lors de l'installation un Makefile sera créé s'il n'est pas présent, ou une liste de recettes seront ajoutées à votre Makefile s'il existe.

 ```bash
 
include vendor/twc/code-quality-plugin/make/quality.mk

```

### Liste des recettes du Makefile

| Make | Def |
|------|-----|
| make twc.stan | Lance une analyse statique avec ***phpstan***  | 
| make twc.fixer | Fix les standards avec ***php-cs-fixer***  | 
| make twc.fix | Fix les standards avec ***php-code-sniffer***  | 
| make twc.rules | Retourne les erreurs suites au non respect des standards ***php-cs***  | 
| make twc.test | Lancer les tests avec ***phpunit***  | 

# Commandes

Ce composant étant un plugin composer, plusieurs commandes sont disponibles.

| Commande | Def |
|----------|------|
| composer twc:make:install  | Installe un Makefile ou ajoute une extension pour bénéficier des recettes du composant |
| composer twc:hooks:install | Installe des hooks Git à votre projet |
|       ↘ --mode=force | Ecrase vos hooks s'ils existent |
|       ↘ --only-hook=pre-commit | Installe un hook pre-commit à votre projet |
|       ↘ --only-hook=commit-msg | Installe un hook commit-msg à votre projet |
| composer twc:quality:install | Installe un dossier de configuration nommé quality à la racine de votre projet |
|       ↘ --mode=force | Ecrase le dossier quality s'il existe |
| <nobr>composer twc:commit:force "MSGCOMMIT"</nobr> | Permet de désactiver le temps du commit les règles et analyses qualités. ***(A utiliser en cas de force majeure)***<br/> Votre MSGCOMMIT sera préfixé ***[commit-forced]*** |




