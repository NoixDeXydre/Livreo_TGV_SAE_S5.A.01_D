<?php
require __DIR__ . '/includes/fonctions.php';

$pageActive = 'livraisons';

/* =========================================================
   Données d'exemple
   Elles seront remplacées par les lectures en base ; la vue
   n'utilise que les variables ci-dessous.
   ========================================================= */
$aujourdhui = new DateTimeImmutable('2026-10-05');

// statut : recu | en_attente (bon resté sur la tablette du chauffeur, sans réseau)
$tournees = [
    '2026-10-01' => [
        ['numero' => 'BL-0101', 'client' => 'Serres Maury', 'adresse' => '3 chemin des Serres', 'heure' => '08:48', 'statut' => 'recu',
         'colis' => [['numero' => 'C-01', 'pieces' => ['P-3101'], 'masse' => 85]],
         'reserves' => []],
        ['numero' => 'BL-0102', 'client' => 'Garage Fabre', 'adresse' => '5 zone artisanale des Prés', 'heure' => '09:21', 'statut' => 'recu',
         'colis' => [['numero' => 'C-02', 'pieces' => ['P-3102'], 'masse' => 350]],
         'reserves' => []],
        ['numero' => 'BL-0103', 'client' => 'Métallerie Dupuy', 'adresse' => '14 rue des Forges', 'heure' => '10:05', 'statut' => 'recu',
         'colis' => [
             ['numero' => 'C-06', 'pieces' => ['P-3107', 'P-3108', 'P-3109'], 'masse' => 510],
             ['numero' => 'C-07', 'pieces' => ['P-3110', 'P-3111'], 'masse' => 470],
         ],
         'reserves' => ['Coin du colis C-07 enfoncé à la livraison.']],
        ['numero' => 'BL-0104', 'client' => 'Chaudronnerie Bastide', 'adresse' => '27 avenue de l’Industrie', 'heure' => '10:36', 'statut' => 'en_attente',
         'colis' => [
             ['numero' => 'C-03', 'pieces' => ['P-3103', 'P-3104'], 'masse' => 820],
             ['numero' => 'C-04', 'pieces' => ['P-3105'], 'masse' => 410],
         ],
         'reserves' => []],
        ['numero' => 'BL-0105', 'client' => 'Scierie Combes', 'adresse' => '8 route du Moulin', 'heure' => '10:57', 'statut' => 'recu',
         'colis' => [
             ['numero' => 'C-08', 'pieces' => ['P-3112', 'P-3113', 'P-3114'], 'masse' => 760],
             ['numero' => 'C-09', 'pieces' => ['P-3115', 'P-3116', 'P-3117'], 'masse' => 760],
             ['numero' => 'C-10', 'pieces' => ['P-3118', 'P-3119', 'P-3120'], 'masse' => 760],
         ],
         'reserves' => []],
    ],
    '2026-09-24' => [
        ['numero' => 'BL-0098', 'client' => 'Scierie Combes', 'adresse' => '8 route du Moulin', 'heure' => '08:52', 'statut' => 'recu',
         'colis' => [['numero' => 'C-01', 'pieces' => ['P-3071', 'P-3072'], 'masse' => 640]],
         'reserves' => []],
        ['numero' => 'BL-0099', 'client' => 'Garage Fabre', 'adresse' => '5 zone artisanale des Prés', 'heure' => '09:30', 'statut' => 'recu',
         'colis' => [['numero' => 'C-02', 'pieces' => ['P-3073'], 'masse' => 210]],
         'reserves' => []],
        ['numero' => 'BL-0100', 'client' => 'Serres Maury', 'adresse' => '3 chemin des Serres', 'heure' => '10:14', 'statut' => 'recu',
         'colis' => [['numero' => 'C-03', 'pieces' => ['P-3074', 'P-3075'], 'masse' => 160]],
         'reserves' => []],
    ],
    '2026-09-17' => [
        ['numero' => 'BL-0096', 'client' => 'Chaudronnerie Bastide', 'adresse' => '27 avenue de l’Industrie', 'heure' => '09:05', 'statut' => 'recu',
         'colis' => [['numero' => 'C-01', 'pieces' => ['P-3051', 'P-3052', 'P-3053'], 'masse' => 1150]],
         'reserves' => ['Colis livré à l’accueil, atelier fermé.']],
        ['numero' => 'BL-0097', 'client' => 'Métallerie Dupuy', 'adresse' => '14 rue des Forges', 'heure' => '09:48', 'statut' => 'recu',
         'colis' => [['numero' => 'C-02', 'pieces' => ['P-3054'], 'masse' => 150]],
         'reserves' => []],
    ],
];


/* =========================================================
   Préparation de l'affichage
   ========================================================= */
$dateTournee = isset($tournees[$_GET['tournee'] ?? '']) ? $_GET['tournee'] : array_key_first($tournees);
$jourTournee = new DateTimeImmutable($dateTournee);
$bons        = $tournees[$dateTournee];

foreach ($bons as &$bon) {
    $bon['masse'] = array_sum(array_column($bon['colis'], 'masse'));
}
unset($bon);

$enAttente  = array_values(array_filter($bons, fn ($b) => $b['statut'] === 'en_attente'));
$nbRecus    = count($bons) - count($enAttente);
$nbColis    = array_sum(array_map(fn ($b) => count($b['colis']), $bons));

// Sans ?bon= valide, on ouvre le premier bon avec des réserves, sinon le premier de la tournée
$numeros = array_column($bons, 'numero');
$indexBon = array_search($_GET['bon'] ?? '', $numeros, true);
if ($indexBon === false) {
    $avecReserves = array_keys(array_filter($bons, fn ($b) => $b['reserves'] !== []));
    $indexBon = $avecReserves[0] ?? 0;
}
$bonSelectionne = $bons[$indexBon];

$urlBon = fn (string $numero) => 'livraisons.php?' . http_build_query(['tournee' => $dateTournee, 'bon' => $numero]);

$statuts = [
    'recu'       => ['Reçu',               'etiquette--livree',   'coche'],
    'en_attente' => ['En attente d’envoi', 'etiquette--en-cours', 'alerte'],
];

$detailPieces = function (array $pieces): string {
    $texte = pluriel(count($pieces), 'pièce', 'pièces') . ' · ' . $pieces[0];

    return count($pieces) > 1 ? $texte . ' à ' . end($pieces) : $texte;
};
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <!-- Seules les ressources du site sont autorisées (aucun CDN, aucune police externe) -->
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; img-src 'self' data:; object-src 'none'; base-uri 'self'; form-action 'self'">
    <meta name="referrer" content="no-referrer">
    <title>Livraisons · T.G.V Atelier</title>

    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/commun.css">
    <link rel="stylesheet" href="css/pages/livraisons.css">
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
                    <h1 class="titre-l">Livraisons</h1>
                    <p class="texte-m texte-secondaire">Suivi des colis livrés par le chauffeur, d’après les bons de livraison</p>
                </div>
                <form class="entete-page__actions choix-tournee" action="livraisons.php" method="get">
                    <div class="champ">
                        <label class="champ__libelle" for="tournee">Tournée</label>
                        <select class="champ__saisie" id="tournee" name="tournee">
                            <?php foreach (array_keys($tournees) as $date): ?>
                                <option value="<?= e($date) ?>"<?= $date === $dateTournee ? ' selected' : '' ?>><?= e(date_fr(new DateTimeImmutable($date), false, true)) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="champ__icone"><?= icone('chevron-bas') ?></span>
                    </div>
                </form>
            </div>

            <!-- Chiffres de la tournée -->
            <div class="tuiles tuiles--4">
                <div class="tuile">
                    <span class="tuile__valeur"><?= nombre(count($bons)) ?></span>
                    <span class="tuile__libelle"><?= count($bons) > 1 ? 'bons de livraison' : 'bon de livraison' ?></span>
                </div>
                <div class="tuile">
                    <span class="tuile__valeur"><?= nombre($nbRecus) ?></span>
                    <span class="tuile__libelle"><?= $nbRecus > 1 ? 'bons reçus' : 'bon reçu' ?></span>
                </div>
                <div class="tuile">
                    <span class="tuile__valeur"><?= nombre(count($enAttente)) ?></span>
                    <span class="tuile__libelle">en attente d’envoi</span>
                </div>
                <div class="tuile">
                    <span class="tuile__valeur"><?= nombre($nbColis) ?></span>
                    <span class="tuile__libelle">colis remis</span>
                </div>
            </div>

            <?php if ($enAttente !== []):
                $numerosAttente = array_column($enAttente, 'numero'); ?>
                <div class="bandeau bandeau--avertissement" role="status">
                    <?= icone('alerte', 22) ?>
                    <span class="bandeau__texte">
                        <?php if (count($numerosAttente) === 1): ?>
                            Le bon <?= e($numerosAttente[0]) ?> est resté sur la tablette du chauffeur, sans réseau. Il sera reçu dès la prochaine synchronisation.
                        <?php else: ?>
                            Les bons <?= e(implode(', ', $numerosAttente)) ?> sont restés sur la tablette du chauffeur, sans réseau. Ils seront reçus dès la prochaine synchronisation.
                        <?php endif; ?>
                    </span>
                </div>
            <?php endif; ?>

            <div class="grille-livraisons">

                <!-- Bons de livraison de la tournée -->
                <section class="panneau-tableau" aria-label="Bons de livraison de la tournée du <?= e(date_fr($jourTournee)) ?>">
                    <div class="table-responsive">
                        <table class="tableau tableau--livraisons">
                            <thead>
                                <tr>
                                    <th scope="col">Bon</th>
                                    <th scope="col">Client</th>
                                    <th scope="col">Colis</th>
                                    <th scope="col">Masse</th>
                                    <th scope="col">Heure</th>
                                    <th scope="col">Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($bons as $i => $bon):
                                    [$libelleStatut, $classeStatut, $iconeStatut] = $statuts[$bon['statut']];
                                    $selectionne = $i === $indexBon;
                                    $nbReserves  = count($bon['reserves']); ?>
                                    <tr<?= $selectionne ? ' class="est-selectionne"' : '' ?>>
                                        <td><a class="tableau__principal" href="<?= e($urlBon($bon['numero'])) ?>"<?= $selectionne ? ' aria-current="true"' : '' ?>><?= e($bon['numero']) ?></a></td>
                                        <td>
                                            <span class="tableau__principal tableau__principal--fort"><?= e($bon['client']) ?></span>
                                            <?php if ($bon['statut'] === 'en_attente'): ?>
                                                <span class="tableau__detail tableau__detail--avertissement">Tablette hors réseau</span>
                                            <?php elseif ($nbReserves > 0): ?>
                                                <span class="tableau__detail"><?= e(pluriel($nbReserves, 'réserve', 'réserves')) ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td><span class="tableau__principal"><?= e(nombre(count($bon['colis']))) ?> colis</span></td>
                                        <td><span class="tableau__principal"><?= e(nombre($bon['masse'])) ?> kg</span></td>
                                        <td><span class="tableau__principal"><?= e(heure($bon['heure'])) ?></span></td>
                                        <td><span class="etiquette <?= $classeStatut ?>"><?= icone($iconeStatut, 16, 2.2) ?><?= e($libelleStatut) ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </section>

                <!-- Bon sélectionné -->
                <section class="carte bon" aria-labelledby="titre-bon">
                    <div class="bon__entete">
                        <span class="surtitre">Bon sélectionné</span>
                        <h2 class="titre-m" id="titre-bon"><?= e($bonSelectionne['numero']) ?> · <?= e($bonSelectionne['client']) ?></h2>
                        <span class="texte-m texte-secondaire">
                            Livré le <?= e(date_fr($jourTournee)) ?> à <?= e(heure($bonSelectionne['heure'])) ?> · <?= e($bonSelectionne['adresse']) ?>
                        </span>
                    </div>

                    <div>
                        <h3 class="titre-s">Colis remis</h3>
                        <ul class="liste-colis">
                            <?php foreach ($bonSelectionne['colis'] as $colis): ?>
                                <li class="colis">
                                    <span class="texte-secondaire"><?= icone('colis') ?></span>
                                    <span class="colis__texte">
                                        <span class="texte-m fw-medium"><?= e($colis['numero']) ?></span>
                                        <span class="texte-s texte-secondaire"><?= e($detailPieces($colis['pieces'])) ?></span>
                                    </span>
                                    <span class="texte-m"><?= e(nombre($colis['masse'])) ?> kg</span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <div class="bon__reserves">
                        <h3 class="titre-s">Réserves</h3>
                        <?php if ($bonSelectionne['statut'] === 'en_attente'): ?>
                            <p class="texte-m texte-secondaire">Les réserves seront connues à la réception du bon.</p>
                        <?php elseif ($bonSelectionne['reserves'] === []): ?>
                            <p class="texte-m texte-secondaire">Aucune réserve à la livraison.</p>
                        <?php else: ?>
                            <?php foreach ($bonSelectionne['reserves'] as $reserve): ?>
                                <p class="texte-m texte-secondaire"><?= e($reserve) ?></p>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <div class="carte__actions">
                        <a class="btn btn-texte btn-moyen" href="boucle.php?<?= e(http_build_query(['date' => $dateTournee])) ?>">Voir la boucle</a>
                    </div>
                </section>

            </div>

        </main>
    </div>
</div>
</body>
</html>
