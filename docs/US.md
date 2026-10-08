# User Stories — Livreo

Chaque US est rédigée au format **GIVEN / WHEN / THEN**, suivie de ses tests d'acceptation classés en trois catégories :

- **Cas nominaux** : le fonctionnement attendu
- **Cas limites** : les situations aux bornes
- **Cas d'erreurs** : les comportements attendus en cas d'échec

Seules les catégories pertinentes figurent pour chaque US. Chaque section correspond à une issue GitHub (`#numéro`) et peut être copiée telle quelle dans le corps de l'issue.

## Sommaire

- [#3 — Mise en place repository GitHub + GitHub Project](#3--mise-en-place-repository-github--github-project)
- [#4 — Paramétrer outil gestion de projets](#4--paramétrer-outil-gestion-de-projets)
- [#5 — Mise en place de la DoD](#5--mise-en-place-de-la-dod)
- [#6 — Mise en place document IA](#6--mise-en-place-document-ia)
- [#7 — Choix SGBD](#7--choix-sgbd)
- [#8 — Mise en place API avec Spring Boot](#8--mise-en-place-api-avec-spring-boot)
- [#9 — Mise en place projet Android Studio](#9--mise-en-place-projet-android-studio)
- [#10 — Mise en place CI App mobile](#10--mise-en-place-ci-app-mobile)
- [#11 — Mise en place CI API](#11--mise-en-place-ci-api)
- [#12 — Mise en place analyse statique du code](#12--mise-en-place-analyse-statique-du-code)
- [#13 — Prévision des maquettes](#13--prévision-des-maquettes)
- [#14 — Rédaction des US](#14--rédaction-des-us)
- [#15 — Mise en place CD API](#15--mise-en-place-cd-api)

---

## #3 — Mise en place repository GitHub + GitHub Project

> *Formulation initiale : En tant qu'équipe, je veux un dépôt GitHub structuré afin de travailler sans conflits.*

**GIVEN** une équipe qui travaille en parallèle sur l'API, l'application mobile et la base de données
**WHEN** un membre clone le dépôt et commence à travailler sur une US
**THEN** il dispose d'une arborescence claire (un emplacement par applicatif), d'une convention de branches et de commits documentée, de règles de protection sur les branches principales et d'un GitHub Project lié au dépôt, ce qui permet de travailler sans conflits

### Cas nominaux
- [ ] Le README décrit la structure du dépôt (API, App mobile, BD) et la convention de nommage des branches et des commits
- [ ] Un membre crée une branche depuis la branche principale, pousse ses commits et ouvre une PR sans conflit
- [ ] Tous les membres de l'équipe ont accès en écriture au dépôt et au GitHub Project
- [ ] Les fichiers propres à chaque poste (`build/`, `.idea/workspace.xml`, `local.properties`, `target/`…) sont ignorés par le `.gitignore`

### Cas limites
- [ ] Deux membres modifient des fichiers différents en parallèle → les deux PR sont fusionnées sans conflit
- [ ] Deux membres modifient le même fichier → GitHub détecte le conflit sur la PR, qui est résolu avant la fusion (pas d'écrasement silencieux)

### Cas d'erreurs
- [ ] Un push direct sur une branche protégée est refusé
- [ ] Une PR sans relecture approuvée ne peut pas être fusionnée

---

## #4 — Paramétrer outil gestion de projets

> *Formulation initiale : En tant qu'équipe, je veux un outil de suivi afin de piloter les sprints et les heures de travail.*

**GIVEN** un GitHub Project associé au dépôt
**WHEN** l'équipe planifie un sprint et que chaque membre avance sur ses US
**THEN** chaque US apparaît dans le tableau avec son statut, son sprint, son assigné, son estimation et les heures passées, ce qui permet de piloter les sprints et le temps de travail

### Cas nominaux
- [ ] Une issue labellisée « US » est ajoutée au Project et apparaît dans le Backlog
- [ ] Déplacer une US d'une colonne à l'autre (Todo → In Progress → Review → Done) met à jour son statut
- [ ] Une vue filtrée par sprint (itération) n'affiche que les US de ce sprint
- [ ] Les heures saisies sur une US peuvent être consultées et cumulées par sprint et par membre

### Cas limites
- [ ] Une US sans assigné ou sans estimation reste visible dans le Backlog et un filtre permet de la retrouver
- [ ] La fermeture d'une issue la passe automatiquement en « Done »

### Cas d'erreurs
- [ ] Une valeur non numérique dans le champ « heures » ou « estimation » est refusée (champ de type nombre)

---

## #5 — Mise en place de la DoD

> *Formulation initiale : En tant qu'équipe, je veux une DoD écrite afin de savoir quand une US peut être qualifiée de finie.*

**GIVEN** une US en cours de développement
**WHEN** un membre de l'équipe veut la passer à l'état « Terminé »
**THEN** il peut vérifier chaque critère d'une DoD écrite et accessible à toute l'équipe, et l'US n'est déclarée finie que si tous les critères applicables sont remplis

### Cas nominaux
- [ ] La DoD est rédigée, versionnée (dépôt ou wiki) et un lien y mène depuis le README ou le GitHub Project
- [ ] La DoD contient au minimum : critères d'acceptation validés, code relu (PR approuvée), build et tests CI au vert, analyse statique sans point bloquant, documentation à jour, code fusionné sur la branche principale
- [ ] Tous les membres de l'équipe ont relu et validé la DoD

### Cas limites
- [ ] Pour une US sans code (documentation, choix de SGBD…), la DoD précise quels critères s'appliquent

### Cas d'erreurs
- [ ] Une US dont un critère n'est pas rempli (ex. CI au rouge, PR non relue) ne peut pas passer en « Done » et repart en cours

---

## #6 — Mise en place document IA

> *Formulation initiale : En tant qu'équipe, je veux un journal de suivi IA afin d'alimenter IA001/IA002 au fil de l'eau.*

**GIVEN** une équipe qui utilise des outils d'IA pendant le projet
**WHEN** un membre se sert d'une IA pour une tâche
**THEN** il consigne cet usage dans un journal partagé (date, auteur, outil, objectif, prompt ou résumé, résultat, part retenue ou modifiée, regard critique), qui alimente au fil de l'eau les livrables IA001 et IA002

### Cas nominaux
- [ ] Le journal existe, est accessible à toute l'équipe et propose un modèle d'entrée
- [ ] Une nouvelle entrée renseigne tous les champs du modèle
- [ ] Chaque entrée est rattachée à l'US ou à l'issue concernée

### Cas limites
- [ ] Un usage de l'IA dont le résultat n'a pas été retenu est tout de même consigné (avec la mention « non retenu » et la raison)

### Cas d'erreurs
- [ ] Une entrée incomplète (champ obligatoire manquant) est repérée lors de la revue de sprint et complétée

---

## #7 — Choix SGBD

> *Formulation initiale : En tant qu'équipe, je veux un choix de SGBD justifié afin d'être prêt à commencer la réflexion sur les choix de conception de la BD.*

**GIVEN** les besoins en données de Livreo (clients, commandes, tournées, véhicules, dépôts)
**WHEN** l'équipe compare plusieurs SGBD (ex. MongoDB, PostgreSQL…)
**THEN** un document présente les critères de comparaison et les options étudiées, puis justifie le SGBD retenu, pour que l'équipe puisse commencer la conception de la BD

### Cas nominaux
- [ ] Le document compare au moins 2 SGBD selon des critères explicites : modèle de données, performances, scalabilité, intégration avec Spring Boot, hébergement, coût, compétences de l'équipe
- [ ] Le choix final est justifié au regard de ces critères et validé par l'équipe
- [ ] Le SGBD retenu s'installe et se lance, et un script d'initialisation crée les collections ou tables de base

### Cas limites
- [ ] Les critères sur lesquels le SGBD retenu est moins performant sont identifiés, et le document explique pourquoi ces limites sont acceptées

---

## #8 — Mise en place API avec Spring Boot

> *Formulation initiale : En tant que développeur, je veux un squelette Spring Boot (Maven) afin d'avoir une structure d'API propre pour développer sur les prochains sprints.*

**GIVEN** un poste développeur équipé du JDK 21 (Maven ou le wrapper `mvnw`)
**WHEN** un développeur clone le dépôt et lance l'API
**THEN** le projet compile, les tests passent et l'API démarre en exposant un endpoint de test, avec une arborescence en couches (controller, service, repository, model, dto…) prête pour les prochains sprints

### Cas nominaux
- [ ] `./mvnw clean package` → BUILD SUCCESS, le jar est généré
- [ ] `./mvnw test` → le test de chargement du contexte Spring passe
- [ ] Une fois l'API lancée, un GET sur l'endpoint de test renvoie 200 avec la réponse attendue
- [ ] Les packages `controller`, `service`, `repository` et `model` existent
- [ ] L'image Docker se construit et, une fois lancée, l'API répond sur le port 8080

### Cas limites
- [ ] Le port et l'URI de la base MongoDB se configurent par `application.properties` ou par variable d'environnement (aucune valeur codée en dur)

### Cas d'erreurs
- [ ] Un appel sur une route inexistante renvoie 404
- [ ] Si la base MongoDB est injoignable, un message d'erreur explicite apparaît dans les logs (pas d'échec silencieux)
- [ ] Avec un JDK inférieur à 21, le build échoue avec un message explicite sur la version Java

---

## #9 — Mise en place projet Android Studio

> *Formulation initiale : En tant que développeur, je veux un projet Android Studio structuré afin de démarrer le développement sur les prochains sprints.*

**GIVEN** un poste développeur équipé d'Android Studio et du SDK Android
**WHEN** un développeur clone le dépôt et ouvre le projet Android
**THEN** la synchronisation Gradle réussit, l'application compile et se lance sur un émulateur ou un appareil, et la structure de packages (ex. `ui`, `data`, `model`, `network`) est prête pour les prochains sprints

### Cas nominaux
- [ ] La synchronisation Gradle se termine sans erreur
- [ ] `./gradlew assembleDebug` → l'APK est généré
- [ ] Une fois l'application installée sur un émulateur, l'écran principal s'affiche sans crash
- [ ] `./gradlew test` → les tests unitaires d'exemple passent
- [ ] Les fichiers propres à chaque poste (`local.properties`, `build/`, `.idea/workspace.xml`) ne sont pas versionnés

### Cas limites
- [ ] L'application démarre sur un appareil au `minSdk` (API 24, Android 7.0)
- [ ] L'application démarre sur un appareil au `targetSdk`
- [ ] Une rotation de l'écran ne provoque pas de crash

---

## #10 — Mise en place CI App mobile

> *Formulation initiale : En tant qu'équipe, je veux que chaque push sur la branche de l'application mobile lance un build et les tests afin de détecter des régressions.*

**GIVEN** un workflow GitHub Actions configuré pour l'application mobile
**WHEN** un membre pousse un commit ou ouvre une PR sur la branche de l'application mobile
**THEN** le workflow compile l'application et exécute les tests, et le résultat (succès ou échec) s'affiche sur le commit ou la PR pour détecter les régressions

### Cas nominaux
- [ ] Un push d'un commit valide déclenche automatiquement le workflow ; build et tests passent, statut ✅
- [ ] L'ouverture d'une PR vers la branche mobile déclenche le workflow, et le statut s'affiche sur la PR
- [ ] Les rapports de tests sont consultables depuis l'exécution du workflow

### Cas limites
- [ ] Un push qui ne modifie aucun fichier de l'application mobile ne déclenche pas le workflow (filtre `paths`)
- [ ] Après deux push rapprochés, le statut final correspond bien au dernier commit

### Cas d'erreurs
- [ ] Un commit contenant une erreur de compilation fait échouer le workflow ❌, avec un log explicite sur l'étape de build
- [ ] Un commit qui fait échouer un test unitaire fait échouer le workflow ❌, et le test en cause est identifié dans le log
- [ ] Une PR dont la CI est rouge ne peut pas être fusionnée

---

## #11 — Mise en place CI API

> *Formulation initiale : En tant qu'équipe, je veux que chaque push sur la branche de l'API lance un build et les tests afin de détecter les régressions.*

**GIVEN** un workflow GitHub Actions configuré pour l'API Spring Boot
**WHEN** un membre pousse un commit ou ouvre une PR sur la branche de l'API
**THEN** le workflow compile l'API avec Maven et exécute les tests, et le résultat (succès ou échec) s'affiche sur le commit ou la PR pour détecter les régressions

### Cas nominaux
- [ ] Un push d'un commit valide déclenche automatiquement le workflow ; `mvn verify` réussit, statut ✅
- [ ] L'ouverture d'une PR vers la branche API déclenche le workflow, et le statut s'affiche sur la PR
- [ ] Les rapports de tests (Surefire) sont consultables depuis l'exécution du workflow

### Cas limites
- [ ] Un push qui ne modifie aucun fichier de l'API ne déclenche pas le workflow (filtre `paths`)
- [ ] Les tests qui ont besoin d'une base MongoDB s'exécutent dans la CI sans base externe (ex. service conteneurisé)
- [ ] Après deux push rapprochés, le statut final correspond bien au dernier commit

### Cas d'erreurs
- [ ] Un commit contenant une erreur de compilation fait échouer le workflow ❌, avec un log explicite sur l'étape de build
- [ ] Un commit qui fait échouer un test fait échouer le workflow ❌, et le test en cause est identifié dans le log
- [ ] Une PR dont la CI est rouge ne peut pas être fusionnée

---

## #12 — Mise en place analyse statique du code

> *Formulation initiale : En tant que développeur, je veux pouvoir avoir une analyse statique du code de chaque applicatif afin d'avoir un retour sur la qualité statique du code fourni.*

**GIVEN** un outil d'analyse statique configuré pour chaque applicatif (API Spring Boot et application Android ; ex. SonarQube Cloud, Android Lint)
**WHEN** un membre pousse du code ou ouvre une PR
**THEN** une analyse se lance automatiquement et produit un rapport consultable (bugs, code smells, vulnérabilités, duplication, couverture), avec une quality gate qui indique si la qualité du code est acceptable

### Cas nominaux
- [ ] Un push sur l'API lance l'analyse, et le rapport est consultable
- [ ] Un push sur l'application mobile lance l'analyse, et le rapport est consultable
- [ ] Le résultat de la quality gate s'affiche sur la PR

### Cas limites
- [ ] Du code sans aucun problème donne un rapport sans anomalie et une quality gate ✅
- [ ] Le nouveau code non couvert par des tests est signalé dans le rapport de couverture

### Cas d'erreurs
- [ ] Un problème volontairement introduit (ex. variable inutilisée, risque de `NullPointerException`) est détecté et signalé
- [ ] Une quality gate en échec ❌ met la PR en statut d'échec
- [ ] Si l'analyse ne peut pas se lancer (token invalide, service indisponible), le job est en échec visible, et non en succès silencieux

---

## #13 — Prévision des maquettes

> *Formulation initiale : En tant que client, je veux avoir un aperçu des maquettes qui vont être utilisées dans le développement de l'application avant que le développement commence afin de demander d'éventuelles modifications de celles-ci.*

**GIVEN** les maquettes des écrans principaux de l'application, réalisées avant le début du développement
**WHEN** elles sont présentées au client
**THEN** le client peut les consulter et demander des modifications, puis les maquettes validées servent de référence pour le développement

### Cas nominaux
- [ ] Les maquettes couvrent tous les écrans prévus pour la première version et sont partagées au client par un lien accessible
- [ ] Le client peut commenter les maquettes
- [ ] Chaque demande de modification est tracée (issue ou commentaire) et intégrée dans une nouvelle version des maquettes
- [ ] La validation du client est consignée (date et version) avant le début du développement

### Cas limites
- [ ] Si le client ne demande aucune modification, la validation directe est tout de même consignée
- [ ] Une demande hors périmètre est consignée, évaluée, puis reportée au backlog si nécessaire

---

## #14 — Rédaction des US

**GIVEN** le cahier des charges du projet Livreo et le GitHub Project de l'équipe
**WHEN** l'équipe rédige le backlog
**THEN** chaque US existe sous forme d'issue labellisée « US », rédigée au format GIVEN / WHEN / THEN, accompagnée de ses tests d'acceptation (cas nominaux, cas limites, cas d'erreurs) et rattachée au GitHub Project

### Cas nominaux
- [ ] Chaque issue labellisée « US » respecte le format GIVEN / WHEN / THEN
- [ ] Chaque US comporte des tests d'acceptation classés par catégorie (seules les catégories pertinentes sont présentes)
- [ ] Chaque US porte les labels de son périmètre (API, App mobile, CI/CD…) et figure dans le GitHub Project

### Cas limites
- [ ] Une US non testable automatiquement (documentation, choix technique) a des critères de validation manuels
- [ ] Une US trop grosse pour un seul sprint est découpée en plusieurs US

### Cas d'erreurs
- [ ] Une US sans critère d'acceptation n'est pas considérée comme « prête » et ne peut pas entrer dans un sprint

---

## #15 — Mise en place CD API

**GIVEN** une CI de l'API au vert sur la branche de l'API et un environnement de déploiement configuré
**WHEN** une modification est poussée ou fusionnée sur la branche de l'API
**THEN** l'API est automatiquement construite en image Docker, publiée dans un registre puis déployée sur l'environnement cible, où la nouvelle version est accessible

### Cas nominaux
- [ ] Un push sur la branche API avec une CI verte lance automatiquement le déploiement
- [ ] Après le déploiement, l'endpoint de test répond 200 depuis l'URL de l'environnement cible
- [ ] L'image Docker est publiée dans le registre avec un tag identifiable (ex. SHA du commit)

### Cas limites
- [ ] Après deux déploiements rapprochés, c'est la version du dernier commit qui est en ligne
- [ ] Un push sur une autre branche ne déclenche aucun déploiement

### Cas d'erreurs
- [ ] Si la CI échoue, aucun déploiement n'a lieu
- [ ] Si le déploiement échoue (ex. le conteneur ne démarre pas), le job est en échec visible et la version précédente reste en ligne ou peut être redéployée
- [ ] Si les secrets (registre, hébergeur) sont absents ou invalides, le job échoue explicitement sans exposer leurs valeurs dans les logs
