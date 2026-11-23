<?php
require __DIR__ . '/includes/fonctions.php';

$pageActive = 'commandes';

/* =========================================================
   Données d'exemple
   Elles seront remplacées par les lectures en base ; la vue
   n'utilise que les variables ci-dessous.
   ========================================================= */
$aujourdhui = new DateTimeImmutable('2026-10-05');

$ateliers = ['Atelier principal'];

// etat : a_faire | en_fabrication | prete | livree
$commandes = [
    ['id' => 115, 'numero' => 'CMD-115', 'client' => 'Scierie Combes',        'adresse' => '8 route du Moulin',          'pieces' => 2, 'masse' => 640,  'date_prevue' => '2026-10-08', 'fabriquees' => 1, 'etat' => 'en_fabrication', 'livree_le' => null,         'atelier' => 'Atelier principal'],
    ['id' => 116, 'numero' => 'CMD-116', 'client' => 'Chaudronnerie Bastide', 'adresse' => '27 avenue de l’Industrie',   'pieces' => 3, 'masse' => 1150, 'date_prevue' => '2026-10-08', 'fabriquees' => 1, 'etat' => 'en_fabrication', 'livree_le' => null,         'atelier' => 'Atelier principal'],
    ['id' => 117, 'numero' => 'CMD-117', 'client' => 'Métallerie Dupuy',      'adresse' => '14 rue des Forges',          'pieces' => 4, 'masse' => 720,  'date_prevue' => '2026-10-08', 'fabriquees' => 2, 'etat' => 'en_fabrication', 'livree_le' => null,         'atelier' => 'Atelier principal'],
    ['id' => 118, 'numero' => 'CMD-118', 'client' => 'Garage Fabre',          'adresse' => '5 zone artisanale des Prés', 'pieces' => 2, 'masse' => 350,  'date_prevue' => '2026-10-08', 'fabriquees' => 2, 'etat' => 'prete',          'livree_le' => null,         'atelier' => 'Atelier principal'],
    ['id' => 119, 'numero' => 'CMD-119', 'client' => 'Serres Maury',          'adresse' => '3 chemin des Serres',        'pieces' => 1, 'masse' => 85,   'date_prevue' => '2026-10-08', 'fabriquees' => 1, 'etat' => 'prete',          'livree_le' => null,         'atelier' => 'Atelier principal'],
    ['id' => 120, 'numero' => 'CMD-120', 'client' => 'Serres Maury',          'adresse' => '3 chemin des Serres',        'pieces' => 3, 'masse' => 240,  'date_prevue' => '2026-10-15', 'fabriquees' => 0, 'etat' => 'a_faire',        'livree_le' => null,         'atelier' => 'Atelier principal'],
    ['id' => 121, 'numero' => 'CMD-121', 'client' => 'Métallerie Dupuy',      'adresse' => '14 rue des Forges',          'pieces' => 1, 'masse' => 150,  'date_prevue' => '2026-10-15', 'fabriquees' => 0, 'etat' => 'a_faire',        'livree_le' => null,         'atelier' => 'Atelier principal'],
    ['id' => 110, 'numero' => 'CMD-110', 'client' => 'Scierie Combes',        'adresse' => '8 route du Moulin',          'pieces' => 9, 'masse' => 2280, 'date_prevue' => '2026-10-01', 'fabriquees' => 9, 'etat' => 'livree',         'livree_le' => '2026-10-01', 'atelier' => 'Atelier principal'],
    ['id' => 111, 'numero' => 'CMD-111', 'client' => 'Chaudronnerie Bastide', 'adresse' => '27 avenue de l’Industrie',   'pieces' => 3, 'masse' => 1230, 'date_prevue' => '2026-10-01', 'fabriquees' => 3, 'etat' => 'livree',         'livree_le' => '2026-10-01', 'atelier' => 'Atelier principal'],
    ['id' => 112, 'numero' => 'CMD-112', 'client' => 'Garage Fabre',          'adresse' => '5 zone artisanale des Prés', 'pieces' => 1, 'masse' => 350,  'date_prevue' => '2026-10-01', 'fabriquees' => 1, 'etat' => 'livree',         'livree_le' => '2026-10-01', 'atelier' => 'Atelier principal'],
    ['id' => 113, 'numero' => 'CMD-113', 'client' => 'Serres Maury',          'adresse' => '3 chemin des Serres',        'pieces' => 1, 'masse' => 85,   'date_prevue' => '2026-10-01', 'fabriquees' => 1, 'etat' => 'livree',         'livree_le' => '2026-10-01', 'atelier' => 'Atelier principal'],
    ['id' => 114, 'numero' => 'CMD-114', 'client' => 'Métallerie Dupuy',      'adresse' => '14 rue des Forges',          'pieces' => 5, 'masse' => 980,  'date_prevue' => '2026-10-01', 'fabriquees' => 5, 'etat' => 'livree',         'livree_le' => '2026-10-01', 'atelier' => 'Atelier principal'],
];


/* =========================================================
   Préparation de l'affichage
   ========================================================= */
$etatsCommande = [
    'a_faire'        => ['À faire',        'etiquette--a-faire'],
    'en_fabrication' => ['En fabrication', 'etiquette--en-cours'],
    'prete'          => ['Prête',          'etiquette--prete'],
    'livree'         => ['Livrée',         'etiquette--livree'],
];

// Filtres passés dans l'URL (?filtre=…&atelier=…) ; une valeur inconnue revient au défaut
$filtres = [
    'toutes'   => ['Toutes',   fn ($c) => true],
    'a-livrer' => ['À livrer', fn ($c) => $c['etat'] !== 'livree'],
    'pretes'   => ['Prêtes',   fn ($c) => $c['etat'] === 'prete'],
    'livrees'  => ['Livrées',  fn ($c) => $c['etat'] === 'livree'],
];
$filtreActif  = array_key_exists($_GET['filtre'] ?? '', $filtres) ? $_GET['filtre'] : 'toutes';
$atelierActif = in_array($_GET['atelier'] ?? '', $ateliers, true) ? $_GET['atelier'] : '';

$commandesAtelier = array_filter($commandes, fn ($c) => $atelierActif === '' || $c['atelier'] === $atelierActif);
$commandesAffichees = array_filter($commandesAtelier, $filtres[$filtreActif][1]);

$urlFiltre = fn (string $filtre) => 'commandes.php?' . http_build_query(array_filter(['filtre' => $filtre, 'atelier' => $atelierActif]));

$totalPieces = array_sum(array_column($commandesAffichees, 'pieces'));
$totalMasse  = array_sum(array_column($commandesAffichees, 'masse'));
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Seules les ressources du site sont autorisées (aucun CDN, aucune police externe) -->
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; img-src 'self' data:; object-src 'none'; base-uri 'self'; form-action 'self'">
    <meta name="referrer" content="no-referrer">
    <title>Commandes · T.G.V Atelier</title>

    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/commun.css">
    <link rel="stylesheet" href="css/pages/commandes.css">
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
                    <h1 class="titre-l">Commandes</h1>
                    <p class="texte-m texte-secondaire">Saisie et suivi des commandes prêtes à être livrées</p>
                </div>
                <div class="entete-page__actions">
                    <a class="btn btn-primary btn-moyen" href="commande.php"><?= icone('plus', 20) ?>Nouvelle commande</a>
                </div>
            </div>

            <!-- Filtres -->
            <div class="barre-filtres">
                <nav class="filtres" aria-label="Filtrer les commandes">
                    <?php foreach ($filtres as $cle => [$libelle, $test]): ?>
                        <a class="filtre" href="<?= e($urlFiltre($cle)) ?>"<?= $cle === $filtreActif ? ' aria-current="true"' : '' ?>>
                            <?= e($libelle) ?>
                            <span class="filtre__nombre"><?= count(array_filter($commandesAtelier, $test)) ?></span>
                        </a>
                    <?php endforeach; ?>
                </nav>

                <form class="liste-deroulante" action="commandes.php" method="get">
                    <input type="hidden" name="filtre" value="<?= e($filtreActif) ?>">
                    <select class="liste-deroulante__choix" name="atelier" aria-label="Filtrer par atelier">
                        <option value="">Tous les ateliers</option>
                        <?php foreach ($ateliers as $atelier): ?>
                            <option value="<?= e($atelier) ?>"<?= $atelier === $atelierActif ? ' selected' : '' ?>><?= e($atelier) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span class="liste-deroulante__icone"><?= icone('chevron-bas') ?></span>
                </form>
            </div>

            <!-- Liste des commandes -->
            <section class="panneau-tableau" aria-label="Liste des commandes">
                <div class="table-responsive">
                    <table class="tableau tableau--commandes">
                        <thead>
                            <tr>
                                <th scope="col">Commande</th>
                                <th scope="col">Client</th>
                                <th scope="col">Pièces · masse</th>
                                <th scope="col">Date prévue</th>
                                <th scope="col">Avancement</th>
                                <th scope="col">État</th>
                                <th scope="col">Atelier</th>
                                <th scope="col"><span class="visually-hidden">Actions</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($commandesAffichees as $commande):
                                [$etatLibelle, $etatClasse] = $etatsCommande[$commande['etat']];
                                $livree = $commande['etat'] === 'livree'; ?>
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
                                    <td><span class="tableau__principal"><?= e(date_courte(new DateTimeImmutable($commande['date_prevue']))) ?></span></td>
                                    <td>
                                        <span class="tableau__detail">
                                            <?php if ($livree): ?>
                                                Livrée le <?= e(date_courte(new DateTimeImmutable($commande['livree_le']), false)) ?>
                                            <?php else: ?>
                                                <?= e($commande['fabriquees']) ?> / <?= e($commande['pieces']) ?> <?= $commande['fabriquees'] > 1 ? 'fabriquées' : 'fabriquée' ?>
                                            <?php endif; ?>
                                        </span>
                                        <progress class="progression<?= $livree ? ' progression--succes' : '' ?>" value="<?= e($commande['fabriquees']) ?>" max="<?= e($commande['pieces']) ?>"
                                                  aria-label="Avancement de la commande <?= e($commande['numero']) ?>"><?= pourcentage($commande['fabriquees'], $commande['pieces']) ?> %</progress>
                                    </td>
                                    <td><span class="etiquette <?= $etatClasse ?>"><?= e($etatLibelle) ?></span></td>
                                    <td><span class="tableau__detail"><?= e($commande['atelier']) ?></span></td>
                                    <td>
                                        <div class="tableau__actions">
                                            <a class="bouton-icone" href="commande.php?id=<?= e($commande['id']) ?>" aria-label="Modifier <?= e($commande['numero']) ?>" title="Modifier"><?= icone('modifier', 22) ?></a>
                                            <button class="bouton-icone" type="button" aria-label="Supprimer <?= e($commande['numero']) ?>" title="Supprimer"><?= icone('supprimer', 22) ?></button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                            <?php if (!$commandesAffichees): ?>
                                <tr>
                                    <td class="tableau__vide" colspan="8">Aucune commande ne correspond à ce filtre.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <p class="panneau-tableau__pied">
                    <?= e(pluriel(count($commandesAffichees), 'commande', 'commandes')) ?>
                    · <?= e(pluriel($totalPieces, 'pièce', 'pièces')) ?>
                    · <?= e(nombre($totalMasse)) ?> kg
                </p>
            </section>

        </main>
    </div>
</div>
</body>
</html>
