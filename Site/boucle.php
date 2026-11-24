<?php
require __DIR__ . '/includes/fonctions.php';

$pageActive = 'boucles';

/* =========================================================
   Données d'exemple
   Elles seront remplacées par les lectures en base ; la vue
   n'utilise que les variables ci-dessous.
   Sans ?date= connue : boucle du 1er octobre.
   ========================================================= */
$aujourdhui = new DateTimeImmutable('2026-10-05');

// km : distance depuis l'étape précédente ; une boucle à planifier n'a ni ordre, ni distance, ni heure
$bouclesExemple = [
    '2026-10-08' => [
        'statut' => 'a_planifier', 'camion' => 'Camion-plateau 1', 'chauffeur' => 'chauffeur01', 'atelier' => 'Atelier principal',
        'depart' => null, 'retour' => null, 'km_retour' => null, 'algorithme' => null, 'calcul' => null,
        'etapes' => [
            ['client' => 'Scierie Combes',        'pieces' => 2, 'masse' => 640],
            ['client' => 'Chaudronnerie Bastide', 'pieces' => 3, 'masse' => 1150],
            ['client' => 'Métallerie Dupuy',      'pieces' => 4, 'masse' => 720],
            ['client' => 'Garage Fabre',          'pieces' => 2, 'masse' => 350],
            ['client' => 'Serres Maury',          'pieces' => 1, 'masse' => 85],
        ],
    ],
    '2026-10-01' => [
        'statut' => 'terminee', 'camion' => 'Camion-plateau 1', 'chauffeur' => 'chauffeur01', 'atelier' => 'Atelier principal',
        'depart' => '08:20', 'retour' => '11:12', 'km_retour' => 10, 'algorithme' => 'Exacte · codée par l’équipe', 'calcul' => '0,4 s sur le serveur',
        'etapes' => [
            ['client' => 'Serres Maury',          'km' => 12, 'heure' => '08:48', 'colis' => [['C-09', 85]]],
            ['client' => 'Garage Fabre',          'km' => 18, 'heure' => '09:21', 'colis' => [['C-08', 350]]],
            ['client' => 'Métallerie Dupuy',      'km' => 21, 'heure' => '10:05', 'colis' => [['C-06', 510], ['C-07', 470]]],
            ['client' => 'Chaudronnerie Bastide', 'km' => 14, 'heure' => '10:36', 'colis' => [['C-04', 640], ['C-05', 590]]],
            ['client' => 'Scierie Combes',        'km' => 11, 'heure' => '10:57', 'colis' => [['C-01', 820], ['C-02', 760], ['C-03', 700]]],
        ],
    ],
];


/* =========================================================
   Préparation de l'affichage
   ========================================================= */
$date    = isset($bouclesExemple[$_GET['date'] ?? '']) ? $_GET['date'] : '2026-10-01';
$jour    = new DateTimeImmutable($date);
$boucle  = $bouclesExemple[$date];
$etapes  = $boucle['etapes'];
$calculee = $boucle['statut'] !== 'a_planifier';

$statuts = [
    'a_planifier' => ['À planifier', 'etiquette--a-faire', 'horloge'],
    'planifiee'   => ['Planifiée',   'etiquette--prete',   'boucles'],
    'terminee'    => ['Terminée',    'etiquette--livree',  'coche'],
];
[$libelleStatut, $classeStatut, $iconeStatut] = $statuts[$boucle['statut']];

if ($calculee) {
    foreach ($etapes as $i => &$etape) {
        $etape['numero'] = $i + 1;
        $etape['masse']  = array_sum(array_column($etape['colis'], 1));
    }
    unset($etape);

    $totalKm   = array_sum(array_column($etapes, 'km')) + $boucle['km_retour'];
    $nbColis   = array_sum(array_map(fn ($e) => count($e['colis']), $etapes));
    $masse     = array_sum(array_column($etapes, 'masse'));
    $minutes   = intdiv(strtotime($boucle['retour']) - strtotime($boucle['depart']), 60);

    /* Ordre de chargement : le dernier arrêt est chargé en premier, au fond du plateau.
       La liste part du haut de la pile ; à arrêt égal, le colis le plus lourd va dessous. */
    $chargement = [];
    foreach ($etapes as $etape) {
        foreach ($etape['colis'] as [$numero, $masseColis]) {
            $chargement[] = ['numero' => $numero, 'client' => $etape['client'], 'etape' => $etape['numero'], 'masse' => $masseColis];
        }
    }
    usort($chargement, fn ($a, $b) => [$a['etape'], $a['masse']] <=> [$b['etape'], $b['masse']]);
    $masseMax = max(array_column($chargement, 'masse'));
} else {
    $nbPieces = array_sum(array_column($etapes, 'pieces'));
    $masse    = array_sum(array_column($etapes, 'masse'));
}

$infos = [
    'Camion'                => $boucle['camion'],
    'Chauffeur'             => $boucle['chauffeur'],
    'Atelier de chargement' => $boucle['atelier'],
];
if ($calculee) {
    $infos += [
        'Départ'     => heure($boucle['depart']),
        'Retour'     => heure($boucle['retour']),
        'Algorithme' => $boucle['algorithme'],
        'Calcul'     => $boucle['calcul'],
    ];
}

$titreJour = date_fr($jour);
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <!-- Seules les ressources du site sont autorisées (aucun CDN, aucune police externe) -->
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; img-src 'self' data:; object-src 'none'; base-uri 'self'; form-action 'self'">
    <meta name="referrer" content="no-referrer">
    <title>Boucle du <?= e($titreJour) ?> · T.G.V Atelier</title>

    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/commun.css">
    <link rel="stylesheet" href="css/pages/boucle.css">
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
                    <nav class="fil-ariane" aria-label="Fil d’Ariane">
                        <a href="boucles.php">Boucles de livraison</a>
                        <?= icone('chevron-droite', 18) ?>
                        <span aria-current="page"><?= e(ucfirst($titreJour)) ?></span>
                    </nav>
                    <h1 class="titre-l">Boucle du <?= e($titreJour) ?></h1>
                </div>
                <div class="entete-page__actions">
                    <?php if ($calculee): ?>
                        <a class="btn btn-contour btn-moyen" href="livraisons.php?<?= e(http_build_query(['tournee' => $date])) ?>"><?= icone('livraisons', 20) ?>Voir les bons de livraison</a>
                    <?php else: ?>
                        <form action="boucle.php?<?= e(http_build_query(['date' => $date])) ?>" method="post">
                            <button class="btn btn-primary btn-moyen" type="submit"><?= icone('boucles', 20) ?>Calculer la boucle</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

            <span class="etiquette <?= $classeStatut ?>"><?= icone($iconeStatut, 16, 2.2) ?><?= e($libelleStatut) ?></span>

            <!-- Informations de la boucle -->
            <dl class="infos-boucle">
                <?php foreach ($infos as $libelle => $valeur): ?>
                    <div class="infos-boucle__element">
                        <dt class="texte-s texte-secondaire"><?= e($libelle) ?></dt>
                        <dd class="texte-m fw-medium"><?= e($valeur) ?></dd>
                    </div>
                <?php endforeach; ?>
            </dl>

            <!-- Chiffres de la boucle -->
            <div class="tuiles tuiles--5">
                <div class="tuile">
                    <span class="tuile__valeur"><?= $calculee ? e(nombre($totalKm)) . ' km' : '—' ?></span>
                    <span class="tuile__libelle">parcourus</span>
                </div>
                <div class="tuile">
                    <span class="tuile__valeur"><?= e(nombre(count($etapes))) ?></span>
                    <span class="tuile__libelle"><?= count($etapes) > 1 ? 'arrêts' : 'arrêt' ?></span>
                </div>
                <div class="tuile">
                    <span class="tuile__valeur"><?= e(nombre($calculee ? $nbColis : $nbPieces)) ?></span>
                    <span class="tuile__libelle"><?= $calculee ? 'colis' : ($nbPieces > 1 ? 'pièces' : 'pièce') ?></span>
                </div>
                <div class="tuile">
                    <span class="tuile__valeur"><?= e(nombre($masse)) ?> kg</span>
                    <span class="tuile__libelle"><?= $calculee ? 'chargés' : 'à charger' ?></span>
                </div>
                <div class="tuile">
                    <span class="tuile__valeur"><?= $calculee ? e(duree($minutes)) : '—' ?></span>
                    <span class="tuile__libelle">durée</span>
                </div>
            </div>

            <?php if (!$calculee): ?>
                <div class="bandeau bandeau--info" role="status">
                    <?= icone('horloge', 22) ?>
                    <span class="bandeau__texte">Cette boucle n’est pas encore calculée. Le parcours et l’ordre de chargement seront proposés après le calcul.</span>
                </div>
            <?php endif; ?>

            <div class="grille-boucle">

                <?php if ($calculee): ?>
                    <div class="grille-boucle__colonne">

                        <!-- Parcours -->
                        <section class="carte" aria-labelledby="titre-parcours">
                            <div class="d-flex flex-column gap-1">
                                <h2 class="titre-m" id="titre-parcours">Parcours</h2>
                                <p class="texte-m texte-secondaire">Ordre des arrêts et kilomètres entre chaque étape.</p>
                            </div>

                            <ol class="parcours" aria-label="Parcours de la boucle du <?= e($titreJour) ?>">
                                <li class="parcours__arret parcours__arret--atelier">
                                    <span class="repere repere--atelier repere--petit"><?= icone('atelier', 16) ?></span>
                                    <?= e($boucle['atelier']) ?>
                                </li>
                                <?php foreach ($etapes as $etape): ?>
                                    <li class="parcours__trajet">
                                        <span class="visually-hidden">puis</span><?= e($etape['km']) ?> km<?= icone('fleche-droite', 18) ?>
                                    </li>
                                    <li class="parcours__arret">
                                        <span class="repere repere--etape repere--petit"><?= $etape['numero'] ?></span>
                                        <?= e($etape['client']) ?>
                                    </li>
                                <?php endforeach; ?>
                                <li class="parcours__trajet">
                                    <span class="visually-hidden">puis</span><?= e($boucle['km_retour']) ?> km<?= icone('fleche-droite', 18) ?>
                                </li>
                                <li class="parcours__arret parcours__arret--atelier">
                                    <span class="repere repere--atelier repere--petit"><?= icone('atelier', 16) ?></span>
                                    <?= e($boucle['atelier']) ?>
                                </li>
                            </ol>

                            <div class="parcours__total">
                                <span class="texte-m texte-secondaire">Total de la boucle</span>
                                <span class="titre-m"><?= e(nombre($totalKm)) ?> km</span>
                            </div>
                        </section>

                        <!-- Ordre de chargement -->
                        <section class="carte" aria-labelledby="titre-chargement">
                            <div class="d-flex flex-column gap-1">
                                <h2 class="titre-m" id="titre-chargement">Ordre de chargement</h2>
                                <p class="texte-m texte-secondaire">Du haut de la pile (déchargé en 1er) au plateau du camion. Le numéro rond est l’étape de livraison.</p>
                            </div>

                            <ol class="liste-boucle">
                                <?php foreach ($chargement as $i => $colis): ?>
                                    <li class="liste-boucle__element">
                                        <span class="repere repere--etape"><span class="visually-hidden">Étape </span><?= $colis['etape'] ?></span>
                                        <span class="liste-boucle__texte">
                                            <span class="d-flex align-items-center flex-wrap gap-2">
                                                <span class="texte-m fw-medium"><?= e($colis['numero']) ?> · <?= e($colis['client']) ?></span>
                                                <?php if ($i === 0): ?>
                                                    <span class="etiquette etiquette--prete">Haut de pile</span>
                                                <?php elseif ($i === count($chargement) - 1): ?>
                                                    <span class="etiquette etiquette--neutre">Plateau</span>
                                                <?php endif; ?>
                                            </span>
                                            <progress class="progression" max="<?= $masseMax ?>" value="<?= $colis['masse'] ?>" aria-label="Masse du colis <?= e($colis['numero']) ?>"></progress>
                                        </span>
                                        <span class="liste-boucle__valeur texte-m"><?= e(nombre($colis['masse'])) ?> kg</span>
                                    </li>
                                <?php endforeach; ?>
                            </ol>
                        </section>

                    </div>
                <?php endif; ?>

                <!-- Étapes de la boucle -->
                <section class="carte" aria-labelledby="titre-etapes">
                    <div class="d-flex flex-column gap-1">
                        <h2 class="titre-m" id="titre-etapes"><?= $calculee ? 'Étapes de la boucle' : 'Clients à livrer' ?></h2>
                        <p class="texte-m texte-secondaire">
                            <?= $calculee
                                ? 'Chaque étape a un type : aujourd’hui un chargement au départ, des livraisons, puis le retour.'
                                : 'L’ordre de passage sera fixé par le calcul de la boucle.' ?>
                        </p>
                    </div>

                    <ol class="liste-boucle">
                        <?php if ($calculee): ?>
                            <li class="liste-boucle__element liste-boucle__element--etape">
                                <span class="repere repere--atelier">A</span>
                                <span class="liste-boucle__texte">
                                    <span class="d-flex align-items-center flex-wrap gap-2">
                                        <span class="texte-m fw-medium"><?= e($boucle['atelier']) ?></span>
                                        <span class="etiquette etiquette--neutre">Chargement</span>
                                    </span>
                                    <span class="texte-s texte-secondaire">1 chargement · <?= e(nombre($nbColis)) ?> colis · <?= e(nombre($masse)) ?> kg · <?= e(heure($boucle['depart'])) ?></span>
                                </span>
                            </li>
                        <?php endif; ?>

                        <?php foreach ($etapes as $etape): ?>
                            <li class="liste-boucle__element liste-boucle__element--etape">
                                <?php if ($calculee): ?>
                                    <span class="repere repere--etape"><?= $etape['numero'] ?></span>
                                <?php else: ?>
                                    <span class="repere repere--attente"><?= icone('adresse', 18) ?></span>
                                <?php endif; ?>
                                <span class="liste-boucle__texte">
                                    <span class="d-flex align-items-center flex-wrap gap-2">
                                        <span class="texte-m fw-medium"><?= e($etape['client']) ?></span>
                                        <span class="etiquette etiquette--prete">Livraison</span>
                                    </span>
                                    <span class="texte-s texte-secondaire">
                                        <?php if ($calculee): ?>
                                            <?= e(nombre(count($etape['colis']))) ?> colis · <?= e(nombre($etape['masse'])) ?> kg · <?= e(heure($etape['heure'])) ?>
                                        <?php else: ?>
                                            <?= e(pluriel($etape['pieces'], 'pièce', 'pièces')) ?> · <?= e(nombre($etape['masse'])) ?> kg
                                        <?php endif; ?>
                                    </span>
                                </span>
                                <?php if ($calculee): ?>
                                    <span class="liste-boucle__valeur texte-m fw-medium">+<?= e($etape['km']) ?> km</span>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>

                        <?php if ($calculee): ?>
                            <li class="liste-boucle__element liste-boucle__element--etape">
                                <span class="repere repere--atelier">A</span>
                                <span class="liste-boucle__texte">
                                    <span class="d-flex align-items-center flex-wrap gap-2">
                                        <span class="texte-m fw-medium"><?= e($boucle['atelier']) ?></span>
                                        <span class="etiquette etiquette--neutre">Retour atelier</span>
                                    </span>
                                    <span class="texte-s texte-secondaire">Fin de tournée · <?= e(heure($boucle['retour'])) ?></span>
                                </span>
                                <span class="liste-boucle__valeur texte-m fw-medium">+<?= e($boucle['km_retour']) ?> km</span>
                            </li>
                        <?php endif; ?>
                    </ol>
                </section>

            </div>

        </main>
    </div>
</div>
</body>
</html>
