# CODE QUALITY PLUGIN

Plugin composer permettant la mise en place d'outils pour une  analyse qualité et respect des standards sur projet symfony.


# Installation

```

composer install twc/code-quality-plugin --dev

```

Après l'installation plusieurs élements auront été créés.

| Type | Libelle     |   Def     |
|------|-------------|-----------|
| Dossier | quality | Permet de configurer les standards et règles qualités sur votre projet | 
| Fichier | Makefile | Ajoute des recettes  | 

# Makefile

Lors de l'installation un Makefile sera créé s'il n'est pas présent, ou une liste de recettes seront ajoutées à votre Makefile s'il existe.

### Liste des recettes du Makefile

| Make | Def |
|------|-----|
| make twc.stan | Lance une analyse statique avec ***phpstan***  | 
| make twc.insights | Lance une analyse statique avec ***phpinsights***  | 
| make twc.fixer | Fix les standards avec ***php-cs-fixer***  | 
| make twc.fix | Fix les standards avec ***php-code-sniffer***  | 
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




