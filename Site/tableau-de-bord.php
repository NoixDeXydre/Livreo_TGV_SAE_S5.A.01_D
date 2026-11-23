<?php
require __DIR__ . '/includes/fonctions.php';

$pageActive = 'tableau-de-bord';

/* =========================================================
   Données d'exemple
   Elles seront remplacées par les lectures en base ; la vue
   n'utilise que les variables ci-dessous.
   ========================================================= */
$aujourdhui = new DateTimeImmutable('2026-10-05');

$prochaineTournee = [
    'date' => new DateTimeImmutable('2026-10-08'),
    'etat' => 'À planifier par le chauffeur',
];

// etat : a_faire | en_fabrication | prete
$commandes = [
    ['id' => 115, 'numero' => 'CMD-115', 'client' => 'Scierie Combes',        'adresse' => '8 route du Moulin',          'pieces' => 2, 'masse' => 640,  'fabriquees' => 1, 'en_cours' => 1, 'etat' => 'en_fabrication'],
    ['id' => 116, 'numero' => 'CMD-116', 'client' => 'Chaudronnerie Bastide', 'adresse' => '27 avenue de l’Industrie',   'pieces' => 3, 'masse' => 1150, 'fabriquees' => 1, 'en_cours' => 1, 'etat' => 'en_fabrication'],
    ['id' => 117, 'numero' => 'CMD-117', 'client' => 'Métallerie Dupuy',      'adresse' => '14 rue des Forges',          'pieces' => 4, 'masse' => 720,  'fabriquees' => 2, 'en_cours' => 1, 'etat' => 'en_fabrication'],
    ['id' => 118, 'numero' => 'CMD-118', 'client' => 'Garage Fabre',          'adresse' => '5 zone artisanale des Prés', 'pieces' => 2, 'masse' => 350,  'fabriquees' => 2, 'en_cours' => 0, 'etat' => 'prete'],
    ['id' => 119, 'numero' => 'CMD-119', 'client' => 'Serres Maury',          'adresse' => '3 chemin des Serres',        'pieces' => 1, 'masse' => 85,   'fabriquees' => 1, 'en_cours' => 0, 'etat' => 'prete'],
];

$bonsManquants = [
    ['numero' => 'BL-0104', 'client' => 'Chaudronnerie Bastide', 'tournee' => new DateTimeImmutable('2026-10-01')],
];

$derniereTournee = [
    'id'             => 41,
    'date'           => new DateTimeImmutable('2026-10-01'),
    'kilometres'     => 86,
    'duree'          => '2 h 52',
    'clients_livres' => 5,
    'clients'        => 5,
    'colis_remis'    => 9,
    'colis'          => 9,
];

$repartition = [
    'tournees' => [new DateTimeImmutable('2026-10-08'), new DateTimeImmutable('2026-10-15')],
    'a_faire'  => 6,
    'en_cours' => 3,
    'fabriquees' => 7,
];

$organisation = [
    'ateliers'   => ['Atelier principal'],
    'camions'    => ['Camion-plateau 1'],
    'chauffeurs' => ['chauffeur01'],
];


/* =========================================================
   Préparation de l'affichage
   ========================================================= */
$etatsCommande = [
    'a_faire'        => ['À faire',        'etiquette--a-faire', 'cercle'],
    'en_fabrication' => ['En fabrication', 'etiquette--en-cours', 'horloge'],
    'prete'          => ['Prête',          'etiquette--prete',    'coche'],
];

$totalPieces     = array_sum(array_column($commandes, 'pieces'));
$totalFabriquees = array_sum(array_column($commandes, 'fabriquees'));
$totalEnCours    = array_sum(array_column($commandes, 'en_cours'));
$totalAFaire     = $totalPieces - $totalFabriquees - $totalEnCours;
$totalMasse      = array_sum(array_column($commandes, 'masse'));
$totalClients    = count(array_unique(array_column($commandes, 'client')));
$dateTournee     = date_fr($prochaineTournee['date']);
$jourTournee     = strtok($dateTournee, ' ');

// Barre empilée : position et largeur de chaque segment, en %
$repartitionTotal = $repartition['a_faire'] + $repartition['en_cours'] + $repartition['fabriquees'];
$segments = [];
$debut = 0;
foreach (['a_faire' => 'a-faire', 'en_cours' => 'en-cours', 'fabriquees' => 'fabriquee'] as $cle => $classe) {
    $largeur = $repartitionTotal > 0 ? $repartition[$cle] / $repartitionTotal * 100 : 0;
    if ($largeur > 0) {
        $segments[] = ['classe' => $classe, 'valeur' => $repartition[$cle], 'x' => $debut, 'largeur' => $largeur];
    }
    $debut += $largeur;
}
$datesRepartition = implode(' et ', array_map('date_fr', $repartition['tournees']));

$nbBons = count($bonsManquants);
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <!-- Seules les ressources du site sont autorisées (aucun CDN, aucune police externe) -->
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; img-src 'self' data:; object-src 'none'; base-uri 'self'; form-action 'self'">
    <meta name="referrer" content="no-referrer">
    <title>Tableau de bord · T.G.V Atelier</title>

    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/commun.css">
    <link rel="stylesheet" href="css/pages/tableau-de-bord.css">
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
                    <h1 class="titre-l">Tableau de bord</h1>
                    <p class="texte-m texte-secondaire"><?= e(date_fr($aujourdhui, false, true)) ?> · prochaine tournée <?= e($dateTournee) ?></p>
                </div>
                <div class="entete-page__actions">
                    <a class="btn btn-primary btn-moyen" href="commande.php"><?= icone('plus', 20) ?>Nouvelle commande</a>
                </div>
            </div>

            <!-- Bons de livraison non reçus -->
            <?php if ($nbBons > 0): ?>
                <div class="bandeau bandeau--avertissement" role="status">
                    <?= icone('alerte', 22) ?>
                    <p class="bandeau__texte">
                        <b><?= e(pluriel($nbBons, 'bon de livraison', 'bons de livraison')) ?></b>
                        <?= $nbBons > 1 ? 'ne sont pas encore arrivés' : 'n’est pas encore arrivé' ?> :
                        <?= implode(' ; ', array_map(
                            fn ($bon) => e($bon['client']) . ' (' . e($bon['numero']) . '), tournée du ' . e(date_fr($bon['tournee'])),
                            $bonsManquants
                        )) ?>.
                        <a href="livraisons.php"><?= $nbBons > 1 ? 'Voir les bons' : 'Voir le bon' ?></a>
                    </p>
                </div>
            <?php endif; ?>

            <div class="grille-principale">

                <!-- Prochaine tournée -->
                <section class="carte" aria-labelledby="titre-prochaine-tournee">
                    <div class="carte__entete">
                        <h2 class="titre-m" id="titre-prochaine-tournee">Tournée du <?= e($dateTournee) ?></h2>
                        <span class="etiquette etiquette--contour etiquette--grande"><?= icone('horloge', 16, 2.2) ?><?= e($prochaineTournee['etat']) ?></span>
                    </div>

                    <div class="avancement">
                        <div class="avancement__ligne">
                            <span class="avancement__titre"><?= e(pluriel($totalFabriquees, 'pièce fabriquée', 'pièces fabriquées')) ?> sur <?= e(nombre($totalPieces)) ?></span>
                            <span class="texte-m texte-secondaire"><?= pourcentage($totalFabriquees, $totalPieces) ?> %</span>
                        </div>
                        <progress class="progression" value="<?= $totalFabriquees ?>" max="<?= $totalPieces ?>"
                                  aria-label="Pièces fabriquées pour la tournée du <?= e($dateTournee) ?>"><?= pourcentage($totalFabriquees, $totalPieces) ?> %</progress>
                        <span class="texte-s texte-secondaire">Il reste <?= e(pluriel($totalEnCours, 'pièce', 'pièces')) ?> en cours et <?= e(nombre($totalAFaire)) ?> à faire d’ici <?= e($jourTournee) ?>.</span>
                    </div>

                    <div class="tuiles">
                        <div class="tuile">
                            <span class="tuile__valeur"><?= e(nombre($totalPieces)) ?></span>
                            <span class="tuile__libelle"><?= $totalPieces > 1 ? 'pièces' : 'pièce' ?></span>
                        </div>
                        <div class="tuile">
                            <span class="tuile__valeur"><?= e(nombre($totalClients)) ?></span>
                            <span class="tuile__libelle"><?= $totalClients > 1 ? 'clients' : 'client' ?></span>
                        </div>
                        <div class="tuile">
                            <span class="tuile__valeur"><?= e(nombre($totalMasse)) ?></span>
                            <span class="tuile__libelle">kg au total</span>
                        </div>
                    </div>

                    <div class="carte__actions">
                        <a class="btn btn-primary btn-moyen" href="fabrication.php"><?= icone('fabrication', 20) ?>Suivi de fabrication</a>
                        <a class="btn btn-contour btn-moyen" href="liste-commandes.php">Voir les commandes</a>
                    </div>
                </section>

                <!-- Dernière tournée -->
                <section class="carte" aria-labelledby="titre-derniere-tournee">
                    <div class="derniere-tournee__entete">
                        <span class="derniere-tournee__icone"><?= icone('valide', 28) ?></span>
                        <div class="d-flex flex-column">
                            <h2 class="derniere-tournee__titre" id="titre-derniere-tournee">Dernière tournée terminée</h2>
                            <span class="texte-s texte-secondaire"><?= e(date_fr($derniereTournee['date'], false, true)) ?> · retour à l’atelier</span>
                        </div>
                    </div>

                    <div class="tuiles tuiles--2">
                        <div class="tuile">
                            <span class="tuile__valeur"><?= e(nombre($derniereTournee['kilometres'])) ?> km</span>
                            <span class="tuile__libelle">parcourus</span>
                        </div>
                        <div class="tuile">
                            <span class="tuile__valeur"><?= e($derniereTournee['duree']) ?></span>
                            <span class="tuile__libelle">durée</span>
                        </div>
                        <div class="tuile">
                            <span class="tuile__valeur"><?= e($derniereTournee['clients_livres']) ?> / <?= e($derniereTournee['clients']) ?></span>
                            <span class="tuile__libelle">clients livrés</span>
                        </div>
                        <div class="tuile">
                            <span class="tuile__valeur"><?= e($derniereTournee['colis_remis']) ?> / <?= e($derniereTournee['colis']) ?></span>
                            <span class="tuile__libelle">colis remis</span>
                        </div>
                    </div>

                    <div class="carte__actions">
                        <a class="btn btn-tonal btn-moyen" href="boucle.php?id=<?= e($derniereTournee['id']) ?>"><?= icone('boucles', 20) ?>Voir la boucle</a>
                        <a class="btn btn-texte btn-moyen" href="livraisons.php">Bons de livraison</a>
                    </div>
                </section>

            </div>

            <!-- Commandes de la prochaine tournée -->
            <section class="carte carte--tableau" aria-labelledby="titre-commandes">
                <div class="carte__entete">
                    <h2 class="titre-m" id="titre-commandes">Commandes à livrer <?= e($dateTournee) ?></h2>
                    <a class="btn btn-texte btn-moyen" href="liste-commandes.php">Toutes les commandes</a>
                </div>

                <div class="table-responsive">
                    <table class="tableau tableau--commandes">
                        <thead>
                            <tr>
                                <th scope="col">Commande</th>
                                <th scope="col">Client</th>
                                <th scope="col">Pièces · masse</th>
                                <th scope="col">Avancement</th>
                                <th scope="col">État</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($commandes as $commande):
                                [$etatLibelle, $etatClasse, $etatIcone] = $etatsCommande[$commande['etat']]; ?>
                                <tr>
                                    <td><a class="tableau__principal" href="commande.php?id=<?= e($commande['id']) ?>"><?= e($commande['numero']) ?></a></td>
                                    <td>
                                        <span class="tableau__principal tableau__principal--fort"><?= e($commande['client']) ?></span>
                                        <span class="tableau__detail"><?= e($commande['adresse']) ?></span>
                                    </td>
                                    <td>
                                        <span class="tableau__principal"><?= e(pluriel($commande['pieces'], 'pièce', 'pièces')) ?></span>
                                        <span class="tableau__detail"><?= e(nombre($commande['masse'])) ?> kg</span>
                                    </td>
                                    <td>
                                        <span class="tableau__detail"><?= e($commande['fabriquees']) ?> / <?= e($commande['pieces']) ?> <?= $commande['fabriquees'] > 1 ? 'fabriquées' : 'fabriquée' ?></span>
                                        <progress class="progression" value="<?= e($commande['fabriquees']) ?>" max="<?= e($commande['pieces']) ?>"
                                                  aria-label="Avancement de la commande <?= e($commande['numero']) ?>"><?= pourcentage($commande['fabriquees'], $commande['pieces']) ?> %</progress>
                                    </td>
                                    <td><span class="etiquette <?= $etatClasse ?>"><?= icone($etatIcone, 16, 2.2) ?><?= e($etatLibelle) ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <div class="grille-principale">

                <!-- Pièces à livrer par état -->
                <section class="carte" aria-labelledby="titre-repartition">
                    <div class="d-flex flex-column gap-1">
                        <h2 class="titre-m" id="titre-repartition">Pièces à livrer par état</h2>
                        <p class="texte-m texte-secondaire"><?= e(pluriel($repartitionTotal, 'pièce', 'pièces')) ?>, <?= e($datesRepartition) ?></p>
                    </div>

                    <div class="repartition">
                        <svg class="repartition__barre" width="100%" height="40" role="img"
                             aria-label="<?= e(pluriel($repartitionTotal, 'pièce', 'pièces')) ?> : <?= e($repartition['a_faire']) ?> à faire, <?= e($repartition['en_cours']) ?> en cours de fabrication, <?= e($repartition['fabriquees']) ?> fabriquées">
                            <?php foreach ($segments as $segment): ?>
                                <g class="repartition__segment repartition__segment--<?= $segment['classe'] ?>">
                                    <rect x="<?= round($segment['x'], 3) ?>%" y="0" width="<?= round($segment['largeur'], 3) ?>%" height="100%"></rect>
                                    <text x="<?= round($segment['x'] + $segment['largeur'] / 2, 3) ?>%" y="50%"><?= e($segment['valeur']) ?></text>
                                </g>
                            <?php endforeach; ?>
                        </svg>
                    </div>

                    <ul class="legende">
                        <li class="legende__element legende__element--a-faire"><?= icone('cercle', 16) ?>À faire</li>
                        <li class="legende__element legende__element--en-cours"><?= icone('horloge', 16) ?>En cours de fabrication</li>
                        <li class="legende__element legende__element--fabriquee"><?= icone('coche', 16, 2.4) ?>Fabriquée</li>
                    </ul>
                </section>

                <!-- Organisation -->
                <section class="carte" aria-labelledby="titre-organisation">
                    <h2 class="titre-m" id="titre-organisation">Organisation</h2>

                    <ul class="organisation">
                        <li class="organisation__element">
                            <span class="texte-secondaire"><?= icone('atelier') ?></span>
                            <span><b><?= e(pluriel(count($organisation['ateliers']), 'atelier', 'ateliers')) ?></b> · <?= e(implode(', ', $organisation['ateliers'])) ?></span>
                        </li>
                        <li class="organisation__element">
                            <span class="texte-secondaire"><?= icone('camion') ?></span>
                            <span><b><?= e(pluriel(count($organisation['camions']), 'camion', 'camions')) ?></b> · <?= e(implode(', ', $organisation['camions'])) ?></span>
                        </li>
                        <li class="organisation__element">
                            <span class="texte-secondaire"><?= icone('personne') ?></span>
                            <span><b><?= e(pluriel(count($organisation['chauffeurs']), 'chauffeur', 'chauffeurs')) ?></b> · <?= e(implode(', ', $organisation['chauffeurs'])) ?></span>
                        </li>
                    </ul>

                    <a class="btn btn-texte btn-moyen" href="ressources.php">Gérer les ressources</a>
                </section>

            </div>

        </main>
    </div>
</div>
</body>
</html>
