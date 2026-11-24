<?php
require __DIR__ . '/includes/fonctions.php';

$pageActive = 'boucles';

/* =========================================================
   Données d'exemple
   Elles seront remplacées par les lectures en base ; la vue
   n'utilise que les variables ci-dessous.
   ========================================================= */
$aujourdhui = new DateTimeImmutable('2026-10-05');

// statut : a_planifier | planifiee | terminee
// Une boucle à planifier n'a pas encore d'algorithme, de distance ni de durée
$boucles = [
    ['date' => '2026-10-08', 'statut' => 'a_planifier', 'camion' => 'Camion-plateau 1', 'chauffeur' => 'chauffeur01', 'atelier' => 'Atelier principal',
     'clients' => 5, 'contenu' => '12 pièces', 'algorithme' => null, 'km' => null, 'minutes' => null],
    ['date' => '2026-10-01', 'statut' => 'terminee', 'camion' => 'Camion-plateau 1', 'chauffeur' => 'chauffeur01', 'atelier' => 'Atelier principal',
     'clients' => 5, 'contenu' => '9 colis', 'algorithme' => 'Exacte · équipe', 'km' => 86, 'minutes' => 172],
    ['date' => '2026-09-24', 'statut' => 'terminee', 'camion' => 'Camion-plateau 1', 'chauffeur' => 'chauffeur01', 'atelier' => 'Atelier principal',
     'clients' => 4, 'contenu' => '7 colis', 'algorithme' => 'Exacte · bibliothèque', 'km' => 92, 'minutes' => 182],
    ['date' => '2026-09-17', 'statut' => 'terminee', 'camion' => 'Camion-plateau 1', 'chauffeur' => 'chauffeur01', 'atelier' => 'Atelier principal',
     'clients' => 5, 'contenu' => '8 colis', 'algorithme' => 'Exacte · équipe', 'km' => 78, 'minutes' => 153],
    ['date' => '2026-09-10', 'statut' => 'terminee', 'camion' => 'Camion-plateau 1', 'chauffeur' => 'chauffeur01', 'atelier' => 'Atelier principal',
     'clients' => 3, 'contenu' => '6 colis', 'algorithme' => 'Approchée · équipe', 'km' => 81, 'minutes' => 160],
    ['date' => '2026-09-03', 'statut' => 'terminee', 'camion' => 'Camion-plateau 1', 'chauffeur' => 'chauffeur01', 'atelier' => 'Atelier principal',
     'clients' => 5, 'contenu' => '10 colis', 'algorithme' => 'Exacte · équipe', 'km' => 95, 'minutes' => 190],
    ['date' => '2026-08-27', 'statut' => 'terminee', 'camion' => 'Camion-plateau 1', 'chauffeur' => 'chauffeur01', 'atelier' => 'Atelier principal',
     'clients' => 4, 'contenu' => '6 colis', 'algorithme' => 'Exacte · équipe', 'km' => 84, 'minutes' => 168],
    ['date' => '2026-08-20', 'statut' => 'terminee', 'camion' => 'Camion-plateau 1', 'chauffeur' => 'chauffeur01', 'atelier' => 'Atelier principal',
     'clients' => 3, 'contenu' => '5 colis', 'algorithme' => 'Approchée · bibliothèque', 'km' => 73, 'minutes' => 141],
];


/* =========================================================
   Préparation de l'affichage
   ========================================================= */
$periodes = [
    'dernieres' => '5 dernières tournées',
    '30-jours'  => '30 derniers jours',
    'annee'     => 'Cette année',
];

$filtres = [
    'atelier'   => ['Atelier',   'Tous les ateliers'],
    'camion'    => ['Camion',    'Tous les camions'],
    'chauffeur' => ['Chauffeur', 'Tous les chauffeurs'],
];

$periodeActive = isset($periodes[$_GET['periode'] ?? '']) ? $_GET['periode'] : 'dernieres';

// Valeurs proposées dans chaque liste, et valeur retenue (vide = toutes)
$choix = [];
$valeurs = [];
foreach (array_keys($filtres) as $cle) {
    $choix[$cle]   = array_values(array_unique(array_column($boucles, $cle)));
    $valeurs[$cle] = in_array($_GET[$cle] ?? '', $choix[$cle], true) ? $_GET[$cle] : '';
}

$bouclesVisibles = array_filter($boucles, function (array $b) use ($valeurs, $periodeActive, $aujourdhui): bool {
    foreach ($valeurs as $cle => $valeur) {
        if ($valeur !== '' && $b[$cle] !== $valeur) {
            return false;
        }
    }

    // Les boucles à venir restent visibles quelle que soit la période
    $date = new DateTimeImmutable($b['date']);
    return match ($periodeActive) {
        '30-jours' => $date >= $aujourdhui->modify('-30 days'),
        'annee'    => $date->format('Y') === $aujourdhui->format('Y'),
        default    => true,
    };
});

// Le graphique et le bilan ne portent que sur les boucles terminées, de la plus ancienne à la plus récente
$terminees = array_filter($bouclesVisibles, fn ($b) => $b['statut'] === 'terminee');
usort($terminees, fn ($a, $b) => strcmp($a['date'], $b['date']));
if ($periodeActive === 'dernieres') {
    $terminees = array_slice($terminees, -5);
    $datesGardees = array_column($terminees, 'date');
    $bouclesVisibles = array_filter($bouclesVisibles, fn ($b) => $b['statut'] !== 'terminee' || in_array($b['date'], $datesGardees, true));
}

$nbTerminees = count($terminees);
$kms         = array_column($terminees, 'km');
$totalKm     = array_sum($kms);
$moyenneKm   = $nbTerminees > 0 ? $totalKm / $nbTerminees : 0;
$plusCourte  = $nbTerminees > 0 ? $terminees[array_search(min($kms), $kms, true)] : null;
$plusLongue  = $nbTerminees > 0 ? $terminees[array_search(max($kms), $kms, true)] : null;

$sousTitreGraphique = $periodeActive === 'dernieres'
    ? pluriel($nbTerminees, 'dernière tournée', 'dernières tournées') . ' du jeudi'
    : $periodes[$periodeActive] . ' · ' . pluriel($nbTerminees, 'tournée', 'tournées');

/* Graphique en barres (repère SVG de 600 × 224) :
   la barre la plus haute mesure 142, la base est à y = 190,
   la légende de la moyenne occupe le coin haut gauche */
$graphique = ['base' => 190, 'gauche' => 20, 'largeur' => 560];
$echelle   = $nbTerminees > 0 ? 142 / max($kms) : 0;
$pas       = $nbTerminees > 0 ? $graphique['largeur'] / $nbTerminees : 0;
$largeurBarre = min(64, $pas * 0.6);
$yMoyenne  = $graphique['base'] - $moyenneKm * $echelle;

$statuts = [
    'a_planifier' => ['À planifier', 'etiquette--a-faire', 'horloge'],
    'planifiee'   => ['Planifiée',   'etiquette--prete',   'boucles'],
    'terminee'    => ['Terminée',    'etiquette--livree',  'coche'],
];
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <!-- Seules les ressources du site sont autorisées (aucun CDN, aucune police externe) -->
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; img-src 'self' data:; object-src 'none'; base-uri 'self'; form-action 'self'">
    <meta name="referrer" content="no-referrer">
    <title>Boucles de livraison · T.G.V Atelier</title>

    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/commun.css">
    <link rel="stylesheet" href="css/pages/boucles.css">
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
                    <h1 class="titre-l">Boucles de livraison</h1>
                    <p class="texte-m texte-secondaire">Trajet et kilomètres parcourus par boucle</p>
                </div>
            </div>

            <div class="grille-boucles">

                <!-- Kilomètres par boucle -->
                <section class="carte" aria-labelledby="titre-graphique">
                    <div class="d-flex flex-column gap-1">
                        <h2 class="titre-m" id="titre-graphique">Kilomètres par boucle</h2>
                        <p class="texte-m texte-secondaire"><?= e($sousTitreGraphique) ?></p>
                    </div>

                    <?php if ($nbTerminees === 0): ?>
                        <p class="texte-m texte-secondaire">Aucune boucle terminée pour ces filtres.</p>
                    <?php else: ?>
                        <svg class="graphique" viewBox="0 0 600 224" role="img"
                             aria-label="Kilomètres par boucle : <?= e(implode(', ', $kms)) ?> km, moyenne <?= e(decimal($moyenneKm)) ?> km">
                            <line class="graphique__axe" x1="20" y1="190" x2="580" y2="190"/>
                            <line class="graphique__moyenne" x1="20" y1="<?= round($yMoyenne, 1) ?>" x2="580" y2="<?= round($yMoyenne, 1) ?>"/>
                            <line class="graphique__moyenne" x1="20" y1="12" x2="44" y2="12"/>
                            <text class="graphique__legende" x="52" y="12" dominant-baseline="central">Moyenne <?= e(decimal($moyenneKm)) ?> km</text>

                            <?php foreach ($terminees as $i => $boucle):
                                $hauteur = $boucle['km'] * $echelle;
                                $centre  = $graphique['gauche'] + $pas * ($i + 0.5);
                                $recente = $i === $nbTerminees - 1; ?>
                                <rect class="graphique__barre<?= $recente ? ' graphique__barre--recente' : '' ?>"
                                      x="<?= round($centre - $largeurBarre / 2, 1) ?>" y="<?= round($graphique['base'] - $hauteur, 1) ?>"
                                      width="<?= round($largeurBarre, 1) ?>" height="<?= round($hauteur, 1) ?>" rx="4"/>
                                <text class="graphique__valeur" x="<?= round($centre, 1) ?>" y="<?= round($graphique['base'] - $hauteur - 6, 1) ?>" text-anchor="middle"><?= e($boucle['km']) ?></text>
                                <text class="graphique__date" x="<?= round($centre, 1) ?>" y="212" text-anchor="middle"><?= e(date_courte(new DateTimeImmutable($boucle['date']), false)) ?></text>
                            <?php endforeach; ?>
                        </svg>
                    <?php endif; ?>
                </section>

                <!-- Bilan -->
                <section class="carte" aria-labelledby="titre-bilan">
                    <h2 class="titre-m" id="titre-bilan">Bilan</h2>

                    <div class="tuiles tuiles--2">
                        <div class="tuile">
                            <span class="tuile__valeur"><?= e(nombre($totalKm)) ?> km</span>
                            <span class="tuile__libelle">sur <?= e(pluriel($nbTerminees, 'boucle', 'boucles')) ?></span>
                        </div>
                        <div class="tuile">
                            <span class="tuile__valeur"><?= e(decimal($moyenneKm)) ?> km</span>
                            <span class="tuile__libelle">en moyenne</span>
                        </div>
                        <div class="tuile">
                            <span class="tuile__valeur"><?= $plusCourte ? e(nombre($plusCourte['km'])) . ' km' : '—' ?></span>
                            <span class="tuile__libelle">la plus courte<?= $plusCourte ? ' · ' . e(date_courte(new DateTimeImmutable($plusCourte['date']), false)) : '' ?></span>
                        </div>
                        <div class="tuile">
                            <span class="tuile__valeur"><?= $plusLongue ? e(nombre($plusLongue['km'])) . ' km' : '—' ?></span>
                            <span class="tuile__libelle">la plus longue<?= $plusLongue ? ' · ' . e(date_courte(new DateTimeImmutable($plusLongue['date']), false)) : '' ?></span>
                        </div>
                    </div>
                </section>

            </div>

            <!-- Filtres -->
            <form class="filtres-boucles" action="boucles.php" method="get" aria-label="Filtrer les boucles">
                <div class="champ">
                    <label class="champ__libelle" for="periode">Période</label>
                    <select class="champ__saisie" id="periode" name="periode">
                        <?php foreach ($periodes as $cle => $libelle): ?>
                            <option value="<?= e($cle) ?>"<?= $cle === $periodeActive ? ' selected' : '' ?>><?= e($libelle) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span class="champ__icone"><?= icone('chevron-bas') ?></span>
                </div>

                <?php foreach ($filtres as $cle => [$libelle, $tous]): ?>
                    <div class="champ">
                        <label class="champ__libelle" for="<?= $cle ?>"><?= e($libelle) ?></label>
                        <select class="champ__saisie" id="<?= $cle ?>" name="<?= $cle ?>">
                            <option value=""><?= e($tous) ?></option>
                            <?php foreach ($choix[$cle] as $valeur): ?>
                                <option value="<?= e($valeur) ?>"<?= $valeur === $valeurs[$cle] ? ' selected' : '' ?>><?= e($valeur) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="champ__icone"><?= icone('chevron-bas') ?></span>
                    </div>
                <?php endforeach; ?>
            </form>

            <!-- Liste des boucles -->
            <section class="panneau-tableau" aria-label="Boucles de livraison">
                <div class="table-responsive">
                    <table class="tableau tableau--boucles">
                        <thead>
                            <tr>
                                <th scope="col">Date</th>
                                <th scope="col">Statut</th>
                                <th scope="col">Camion · chauffeur</th>
                                <th scope="col">Atelier de départ</th>
                                <th scope="col">Contenu</th>
                                <th scope="col">Algorithme</th>
                                <th scope="col">Distance</th>
                                <th scope="col">Durée</th>
                                <th scope="col"><span class="visually-hidden">Actions</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($bouclesVisibles as $boucle):
                                [$libelleStatut, $classeStatut, $iconeStatut] = $statuts[$boucle['statut']];
                                $jour     = date_courte(new DateTimeImmutable($boucle['date']));
                                $urlDetail = 'boucle.php?' . http_build_query(['date' => $boucle['date']]); ?>
                                <tr>
                                    <td><a class="tableau__principal" href="<?= e($urlDetail) ?>"><?= e($jour) ?></a></td>
                                    <td><span class="etiquette <?= $classeStatut ?>"><?= icone($iconeStatut, 16, 2.2) ?><?= e($libelleStatut) ?></span></td>
                                    <td>
                                        <span class="tableau__principal"><?= e($boucle['camion']) ?></span>
                                        <span class="tableau__detail"><?= e($boucle['chauffeur']) ?></span>
                                    </td>
                                    <td><span class="tableau__principal"><?= e($boucle['atelier']) ?></span></td>
                                    <td><span class="tableau__principal"><?= e(pluriel($boucle['clients'], 'client', 'clients')) ?> · <?= e($boucle['contenu']) ?></span></td>
                                    <td><span class="tableau__principal"><?= e($boucle['algorithme'] ?? '—') ?></span></td>
                                    <td><span class="tableau__principal tableau__principal--fort"><?= $boucle['km'] !== null ? e(nombre($boucle['km'])) . ' km' : '—' ?></span></td>
                                    <td><span class="tableau__principal"><?= $boucle['minutes'] !== null ? e(duree($boucle['minutes'])) : '—' ?></span></td>
                                    <td>
                                        <div class="tableau__actions">
                                            <a class="bouton-icone" href="<?= e($urlDetail) ?>" aria-label="Ouvrir la boucle du <?= e($jour) ?>" title="Ouvrir la boucle"><?= icone('chevron-droite') ?></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                            <?php if ($bouclesVisibles === []): ?>
                                <tr>
                                    <td class="tableau__vide" colspan="9">Aucune boucle ne correspond à ces filtres.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>

        </main>
    </div>
</div>
</body>
</html>
