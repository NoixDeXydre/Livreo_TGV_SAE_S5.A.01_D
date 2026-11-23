<?php
require __DIR__ . '/includes/fonctions.php';

$pageActive = 'fabrication';

/* =========================================================
   Données d'exemple
   Elles seront remplacées par les lectures en base ; la vue
   n'utilise que les variables ci-dessous.
   ========================================================= */
$aujourdhui = new DateTimeImmutable('2026-10-05');

$ateliers = ['Atelier principal'];

// etat : a_faire | en_cours | fabriquee | livree ; bon : bon de livraison qui verrouille l'état « livrée »
$pieces = [
    ['numero' => 'P-3201', 'designation' => 'Console de levage', 'masse' => 320, 'client' => 'Scierie Combes',        'commande' => 'CMD-115', 'date' => '2026-10-08', 'etat' => 'fabriquee', 'bon' => ''],
    ['numero' => 'P-3202', 'designation' => 'Console de levage', 'masse' => 320, 'client' => 'Scierie Combes',        'commande' => 'CMD-115', 'date' => '2026-10-08', 'etat' => 'en_cours',  'bon' => ''],
    ['numero' => 'P-3203', 'designation' => 'Cuve inox',         'masse' => 480, 'client' => 'Chaudronnerie Bastide', 'commande' => 'CMD-116', 'date' => '2026-10-08', 'etat' => 'fabriquee', 'bon' => ''],
    ['numero' => 'P-3204', 'designation' => 'Trémie',            'masse' => 410, 'client' => 'Chaudronnerie Bastide', 'commande' => 'CMD-116', 'date' => '2026-10-08', 'etat' => 'en_cours',  'bon' => ''],
    ['numero' => 'P-3205', 'designation' => 'Support de cuve',   'masse' => 260, 'client' => 'Chaudronnerie Bastide', 'commande' => 'CMD-116', 'date' => '2026-10-08', 'etat' => 'a_faire',   'bon' => ''],
    ['numero' => 'P-3206', 'designation' => 'Garde-corps 3 m',   'masse' => 180, 'client' => 'Métallerie Dupuy',      'commande' => 'CMD-117', 'date' => '2026-10-08', 'etat' => 'fabriquee', 'bon' => ''],
    ['numero' => 'P-3207', 'designation' => 'Garde-corps 3 m',   'masse' => 180, 'client' => 'Métallerie Dupuy',      'commande' => 'CMD-117', 'date' => '2026-10-08', 'etat' => 'fabriquee', 'bon' => ''],
    ['numero' => 'P-3208', 'designation' => 'Garde-corps 3 m',   'masse' => 180, 'client' => 'Métallerie Dupuy',      'commande' => 'CMD-117', 'date' => '2026-10-08', 'etat' => 'en_cours',  'bon' => ''],
    ['numero' => 'P-3209', 'designation' => 'Garde-corps 3 m',   'masse' => 180, 'client' => 'Métallerie Dupuy',      'commande' => 'CMD-117', 'date' => '2026-10-08', 'etat' => 'a_faire',   'bon' => ''],
    ['numero' => 'P-3210', 'designation' => 'Rail de guidage',   'masse' => 190, 'client' => 'Garage Fabre',          'commande' => 'CMD-118', 'date' => '2026-10-08', 'etat' => 'fabriquee', 'bon' => ''],
    ['numero' => 'P-3211', 'designation' => 'Rail de guidage',   'masse' => 160, 'client' => 'Garage Fabre',          'commande' => 'CMD-118', 'date' => '2026-10-08', 'etat' => 'fabriquee', 'bon' => ''],
    ['numero' => 'P-3212', 'designation' => 'Châssis de serre',  'masse' => 85,  'client' => 'Serres Maury',          'commande' => 'CMD-119', 'date' => '2026-10-08', 'etat' => 'fabriquee', 'bon' => ''],
    ['numero' => 'P-3213', 'designation' => 'Châssis de serre',  'masse' => 85,  'client' => 'Serres Maury',          'commande' => 'CMD-120', 'date' => '2026-10-15', 'etat' => 'a_faire',   'bon' => ''],
    ['numero' => 'P-3214', 'designation' => 'Châssis de serre',  'masse' => 85,  'client' => 'Serres Maury',          'commande' => 'CMD-120', 'date' => '2026-10-15', 'etat' => 'a_faire',   'bon' => ''],
    ['numero' => 'P-3215', 'designation' => 'Porte de serre',    'masse' => 70,  'client' => 'Serres Maury',          'commande' => 'CMD-120', 'date' => '2026-10-15', 'etat' => 'a_faire',   'bon' => ''],
    ['numero' => 'P-3216', 'designation' => 'Poteau HEA 160',    'masse' => 150, 'client' => 'Métallerie Dupuy',      'commande' => 'CMD-121', 'date' => '2026-10-15', 'etat' => 'a_faire',   'bon' => ''],
    ['numero' => 'P-3107', 'designation' => 'Garde-corps 3 m',   'masse' => 180, 'client' => 'Métallerie Dupuy',      'commande' => 'CMD-114', 'date' => '2026-10-01', 'etat' => 'livree',    'bon' => 'BL-0103'],
    ['numero' => 'P-3108', 'designation' => 'Garde-corps 3 m',   'masse' => 180, 'client' => 'Métallerie Dupuy',      'commande' => 'CMD-114', 'date' => '2026-10-01', 'etat' => 'livree',    'bon' => 'BL-0103'],
    ['numero' => 'P-3109', 'designation' => 'Poteau HEA 160',    'masse' => 150, 'client' => 'Métallerie Dupuy',      'commande' => 'CMD-114', 'date' => '2026-10-01', 'etat' => 'livree',    'bon' => 'BL-0103'],
];


/* =========================================================
   Préparation de l'affichage
   ========================================================= */
// Colonnes dans l'ordre du cycle de vie d'une pièce
$colonnes = [
    'a_faire'   => ['titre' => 'À faire',                 'icone' => 'cercle',  'suivant' => 'Démarrer'],
    'en_cours'  => ['titre' => 'En cours de fabrication', 'icone' => 'horloge', 'suivant' => 'Marquer fabriquée'],
    'fabriquee' => ['titre' => 'Fabriquée',               'icone' => 'coche',   'suivant' => 'Marquer livrée'],
    'livree'    => ['titre' => 'Livrée',                  'icone' => 'camion',  'suivant' => null],
];
$ordreEtats = array_keys($colonnes);

// Pièces qui restent à livrer (sans bon de livraison)
$piecesALivrer = array_filter($pieces, fn ($p) => $p['bon'] === '');

// Filtre par date (?date=AAAA-MM-JJ) : les pièces livrées restent toujours visibles
$dates = array_unique(array_column($piecesALivrer, 'date'));
sort($dates);
$dateActive   = in_array($_GET['date'] ?? '', $dates, true) ? $_GET['date'] : '';
$atelierActif = in_array($_GET['atelier'] ?? '', $ateliers, true) ? $_GET['atelier'] : '';

$filtresDate = ['' => ['Toutes les dates', count($piecesALivrer)]];
foreach ($dates as $date) {
    $nb = count(array_filter($piecesALivrer, fn ($p) => $p['date'] === $date));
    $filtresDate[$date] = [date_fr(new DateTimeImmutable($date), false, true), $nb];
}
$urlFiltre = fn (string $date) => 'fabrication.php' . (($q = http_build_query(array_filter(['date' => $date, 'atelier' => $atelierActif]))) ? '?' . $q : '');

$piecesVisibles = array_filter($pieces, fn ($p) => $p['etat'] === 'livree' || $dateActive === '' || $p['date'] === $dateActive);

$nbALivrer   = count(array_filter($piecesALivrer, fn ($p) => $p['etat'] !== 'livree'));
$nbFabriquees = count(array_filter($piecesALivrer, fn ($p) => $p['etat'] === 'fabriquee'));
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <!-- Seules les ressources du site sont autorisées (aucun CDN, aucune police externe) -->
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; img-src 'self' data:; object-src 'none'; base-uri 'self'; form-action 'self'">
    <meta name="referrer" content="no-referrer">
    <title>Suivi de fabrication · T.G.V Atelier</title>

    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/commun.css">
    <link rel="stylesheet" href="css/pages/fabrication.css">
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
                    <h1 class="titre-l">Suivi de fabrication</h1>
                    <p class="texte-m texte-secondaire"><?= e(pluriel($nbALivrer, 'pièce à livrer', 'pièces à livrer')) ?> · <?= e(pluriel($nbFabriquees, 'fabriquée', 'fabriquées')) ?></p>
                </div>
                <div class="entete-page__actions">
                    <a class="btn btn-primary btn-moyen" href="commande.php"><?= icone('plus', 20) ?>Nouvelle commande</a>
                </div>
            </div>

            <!-- Filtres -->
            <div class="barre-filtres">
                <nav class="filtres" aria-label="Filtrer par date de livraison">
                    <?php foreach ($filtresDate as $date => [$libelle, $nb]): ?>
                        <a class="filtre" href="<?= e($urlFiltre($date)) ?>"<?= $date === $dateActive ? ' aria-current="true"' : '' ?>>
                            <?= e($libelle) ?>
                            <span class="filtre__nombre"><?= $nb ?></span>
                        </a>
                    <?php endforeach; ?>
                </nav>

                <form class="liste-deroulante" action="fabrication.php" method="get">
                    <?php if ($dateActive !== ''): ?>
                        <input type="hidden" name="date" value="<?= e($dateActive) ?>">
                    <?php endif; ?>
                    <select class="liste-deroulante__choix" name="atelier" aria-label="Filtrer par atelier">
                        <option value="">Tous les ateliers</option>
                        <?php foreach ($ateliers as $atelier): ?>
                            <option value="<?= e($atelier) ?>"<?= $atelier === $atelierActif ? ' selected' : '' ?>><?= e($atelier) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span class="liste-deroulante__icone"><?= icone('chevron-bas') ?></span>
                </form>
            </div>

            <!-- Tableau de suivi : une colonne par état -->
            <div class="suivi">
                <?php foreach ($colonnes as $etat => $colonne):
                    $rang = array_search($etat, $ordreEtats, true);
                    $piecesColonne = array_filter($piecesVisibles, fn ($p) => $p['etat'] === $etat); ?>
                    <section class="suivi__colonne suivi__colonne--<?= str_replace('_', '-', $etat) ?>" aria-labelledby="titre-<?= $etat ?>">
                        <div class="suivi__entete">
                            <span class="suivi__icone"><?= icone($colonne['icone'], 20) ?></span>
                            <h2 class="suivi__titre titre-s" id="titre-<?= $etat ?>"><?= e($colonne['titre']) ?></h2>
                            <span class="suivi__nombre"><?= count($piecesColonne) ?></span>
                        </div>

                        <?php foreach ($piecesColonne as $piece):
                            $verrouillee = $piece['bon'] !== '' && $etat === 'livree';
                            $precedent   = $rang > 0 && !$verrouillee ? $ordreEtats[$rang - 1] : null;
                            $suivant     = $ordreEtats[$rang + 1] ?? null; ?>
                            <article class="carte-piece">
                                <div class="carte-piece__ligne">
                                    <h3 class="carte-piece__nom"><?= e($piece['designation']) ?></h3>
                                    <span class="texte-s texte-secondaire"><?= e($piece['numero']) ?></span>
                                </div>
                                <span class="texte-s texte-secondaire"><?= e($piece['client']) ?> · <?= e($piece['commande']) ?></span>
                                <span class="carte-piece__details">
                                    <?= e(nombre($piece['masse'])) ?> kg · <?= e(date_courte(new DateTimeImmutable($piece['date']))) ?><?= $verrouillee ? ' · bon ' . e($piece['bon']) : '' ?>
                                </span>

                                <?php if ($precedent || $suivant): ?>
                                    <form class="carte-piece__actions" action="fabrication.php" method="post">
                                        <input type="hidden" name="piece" value="<?= e($piece['numero']) ?>">
                                        <?php if ($precedent): ?>
                                            <button class="bouton-icone bouton-icone--contour" type="submit" name="etat" value="<?= $precedent ?>"
                                                    aria-label="Revenir à « <?= e($colonnes[$precedent]['titre']) ?> » pour <?= e($piece['numero']) ?>"
                                                    title="Revenir à « <?= e($colonnes[$precedent]['titre']) ?> »"><?= icone('retour', 20) ?></button>
                                        <?php endif; ?>
                                        <?php if ($suivant): ?>
                                            <button class="btn btn-tonal btn-moyen carte-piece__suivant" type="submit" name="etat" value="<?= $suivant ?>">
                                                <?= e($colonne['suivant']) ?><span class="visually-hidden"> <?= e($piece['numero']) ?></span>
                                            </button>
                                        <?php endif; ?>
                                    </form>
                                <?php endif; ?>
                            </article>
                        <?php endforeach; ?>

                        <?php if (!$piecesColonne): ?>
                            <p class="suivi__vide texte-m texte-secondaire">Aucune pièce</p>
                        <?php endif; ?>
                    </section>
                <?php endforeach; ?>
            </div>

        </main>
    </div>
</div>
</body>
</html>
