<?php
require __DIR__ . '/includes/fonctions.php';

$pageActive = 'ressources';

/* =========================================================
   Données d'exemple
   Elles seront remplacées par les lectures en base ; la vue
   n'utilise que les variables ci-dessous.
   Les valeurs entre crochets restent à fournir par l'entreprise.
   ========================================================= */
$aujourdhui = new DateTimeImmutable('2026-10-05');

$ateliers = [
    ['nom' => 'Atelier principal', 'adresse' => '[ADRESSE DE L’ATELIER]', 'pieces_en_cours' => 16, 'par_defaut' => true],
];

$camions = [
    ['nom' => 'Camion-plateau 1', 'description' => 'Plateau avec grue de chargement', 'immatriculation' => '[IMMATRICULATION]',
     'derniere_boucle' => ['date' => '2026-10-01', 'km' => 86], 'chauffeur' => 'chauffeur01'],
];

$equipe = [
    ['identifiant' => 'responsable01', 'role' => 'Responsable des commandes', 'outil' => 'Interface Atelier (web)'],
    ['identifiant' => 'chauffeur01',   'role' => 'Chauffeur',                 'outil' => 'Application mobile « T.G.V Livraison »'],
];


/* =========================================================
   Préparation de l'affichage
   ========================================================= */
$onglets = [
    'ateliers' => 'Ateliers',
    'camions'  => 'Camions',
    'equipe'   => 'Équipe',
];
$ongletActif = isset($onglets[$_GET['onglet'] ?? '']) ? $_GET['onglet'] : 'ateliers';
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <!-- Seules les ressources du site sont autorisées (aucun CDN, aucune police externe) -->
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; img-src 'self' data:; object-src 'none'; base-uri 'self'; form-action 'self'">
    <meta name="referrer" content="no-referrer">
    <title><?= e($onglets[$ongletActif]) ?> · Ressources · T.G.V Atelier</title>

    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/commun.css">
    <link rel="stylesheet" href="css/pages/ressources.css">
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js" defer></script>
</head>
<body>
<div class="coque">

    <?php require __DIR__ . '/includes/barre-laterale.php'; ?>

    <div class="coque__colonne">

        <?php require __DIR__ . '/includes/barre-haute.php'; ?>

        <main class="contenu">

            <!-- En-tête de page -->
            <div class="entete-page">
                <div class="entete-page__titres">
                    <h1 class="titre-l">Ressources</h1>
                    <p class="texte-m texte-secondaire">Ateliers, camions et équipe de l’entreprise</p>
                </div>
            </div>

            <nav class="onglets" aria-label="Ressources">
                <?php foreach ($onglets as $cle => $libelle): ?>
                    <a class="onglet" href="ressources.php?onglet=<?= e($cle) ?>"<?= $cle === $ongletActif ? ' aria-current="page"' : '' ?>><?= e($libelle) ?></a>
                <?php endforeach; ?>
            </nav>

            <div class="bandeau bandeau--info">
                <?= icone('info', 22) ?>
                <span class="bandeau__texte">Les commandes, les colis et les boucles sont déjà rattachés à un atelier et à un camion : un seul de chaque est actif pour le moment.</span>
            </div>

            <?php if ($ongletActif === 'ateliers'): ?>
                <!-- Ateliers -->
                <section class="ressources" aria-labelledby="titre-ressources">
                    <div class="ressources__entete">
                        <h2 class="titre-m" id="titre-ressources">Ateliers · <?= count($ateliers) ?></h2>
                    </div>

                    <?php foreach ($ateliers as $atelier): ?>
                        <article class="ressource">
                            <span class="pastille-icone pastille-icone--moyenne"><?= icone('atelier') ?></span>
                            <div class="ressource__texte">
                                <h3 class="titre-s"><?= e($atelier['nom']) ?></h3>
                                <p class="texte-m texte-secondaire">Adresse : <?= e($atelier['adresse']) ?></p>
                                <p class="texte-m texte-secondaire"><?= e(pluriel($atelier['pieces_en_cours'], 'pièce', 'pièces')) ?> à fabriquer ou à livrer</p>
                                <?php if ($atelier['par_defaut']): ?>
                                    <span class="etiquette etiquette--accent ressource__etiquette">Dépôt et point de chargement par défaut</span>
                                <?php endif; ?>
                            </div>
                            <button class="bouton-icone" type="button" aria-label="Modifier <?= e($atelier['nom']) ?>" title="Modifier"><?= icone('modifier', 22) ?></button>
                        </article>
                    <?php endforeach; ?>
                </section>

            <?php elseif ($ongletActif === 'camions'): ?>
                <!-- Camions -->
                <section class="ressources" aria-labelledby="titre-ressources">
                    <div class="ressources__entete">
                        <h2 class="titre-m" id="titre-ressources">Camions · <?= count($camions) ?></h2>
                        <button class="btn btn-tonal btn-moyen" type="button"><?= icone('plus', 20) ?>Ajouter un camion</button>
                    </div>

                    <?php foreach ($camions as $camion): ?>
                        <article class="ressource">
                            <span class="pastille-icone pastille-icone--moyenne"><?= icone('camion') ?></span>
                            <div class="ressource__texte">
                                <h3 class="titre-s"><?= e($camion['nom']) ?></h3>
                                <p class="texte-m texte-secondaire"><?= e($camion['description']) ?></p>
                                <p class="texte-m texte-secondaire">Immatriculation : <?= e($camion['immatriculation']) ?></p>
                                <?php if ($camion['derniere_boucle']): ?>
                                    <p class="texte-m texte-secondaire">
                                        Dernière boucle :
                                        <a href="boucle.php?<?= e(http_build_query(['date' => $camion['derniere_boucle']['date']])) ?>"><?= e(nombre($camion['derniere_boucle']['km'])) ?> km, <?= e(date_fr(new DateTimeImmutable($camion['derniere_boucle']['date']))) ?></a>
                                    </p>
                                <?php endif; ?>
                                <span class="etiquette etiquette--neutre ressource__etiquette">Chauffeur habituel : <?= e($camion['chauffeur']) ?></span>
                            </div>
                            <button class="bouton-icone" type="button" aria-label="Modifier <?= e($camion['nom']) ?>" title="Modifier"><?= icone('modifier', 22) ?></button>
                        </article>
                    <?php endforeach; ?>
                </section>

            <?php else: ?>
                <!-- Équipe -->
                <section class="ressources" aria-labelledby="titre-ressources">
                    <div class="ressources__entete">
                        <h2 class="titre-m" id="titre-ressources">Équipe · <?= e(pluriel(count($equipe), 'personne', 'personnes')) ?></h2>
                        <button class="btn btn-tonal btn-moyen" type="button"><?= icone('plus', 20) ?>Ajouter un membre</button>
                    </div>

                    <?php foreach ($equipe as $membre): ?>
                        <article class="ressource">
                            <span class="pastille-icone pastille-icone--moyenne"><?= icone('personne') ?></span>
                            <div class="ressource__texte">
                                <h3 class="titre-s"><?= e($membre['identifiant']) ?></h3>
                                <p class="texte-m texte-secondaire"><?= e($membre['role']) ?></p>
                                <p class="texte-m texte-secondaire"><?= e($membre['outil']) ?></p>
                            </div>
                            <button class="bouton-icone" type="button" aria-label="Modifier <?= e($membre['identifiant']) ?>" title="Modifier"><?= icone('modifier', 22) ?></button>
                        </article>
                    <?php endforeach; ?>
                </section>
            <?php endif; ?>

        </main>
    </div>
</div>
</body>
</html>
