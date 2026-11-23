<?php
require __DIR__ . '/includes/fonctions.php';

$pageActive = 'commandes';

/* =========================================================
   Données d'exemple
   Elles seront remplacées par les lectures en base ; la vue
   n'utilise que les variables ci-dessous.
   Sans ?id= (ou avec un id inconnu) : formulaire de création.
   ========================================================= */
$aujourdhui = new DateTimeImmutable('2026-10-05');

$clients = [
    1 => ['nom' => 'Scierie Combes',        'adresse' => '8 route du Moulin'],
    2 => ['nom' => 'Chaudronnerie Bastide', 'adresse' => '27 avenue de l’Industrie'],
    3 => ['nom' => 'Métallerie Dupuy',      'adresse' => '14 rue des Forges, 31700 Blagnac'],
    4 => ['nom' => 'Garage Fabre',          'adresse' => '5 zone artisanale des Prés'],
    5 => ['nom' => 'Serres Maury',          'adresse' => '3 chemin des Serres'],
];

$ateliers = [1 => 'Atelier principal'];

// etat d'une pièce : a_faire | en_cours | fabriquee | livree
$commandesExemple = [
    117 => [
        'numero'      => 'CMD-117',
        'client_id'   => 3,
        'date_prevue' => '2026-10-08',
        'atelier_id'  => 1,
        'reference'   => '',
        'colis'       => [720],
        'pieces'      => [
            ['numero' => 'P-3206', 'designation' => 'Garde-corps 3 m', 'masse' => 180, 'etat' => 'fabriquee'],
            ['numero' => 'P-3207', 'designation' => 'Garde-corps 3 m', 'masse' => 180, 'etat' => 'fabriquee'],
            ['numero' => 'P-3208', 'designation' => 'Garde-corps 3 m', 'masse' => 180, 'etat' => 'en_cours'],
            ['numero' => 'P-3209', 'designation' => 'Garde-corps 3 m', 'masse' => 180, 'etat' => 'a_faire'],
        ],
    ],
];


/* =========================================================
   Préparation de l'affichage
   ========================================================= */
$id = (int) ($_GET['id'] ?? 0);
$modification = isset($commandesExemple[$id]);

$commande = $commandesExemple[$id] ?? [
    'numero'      => '',
    // ?client= : client présélectionné (bouton « Nouvelle commande » d'une fiche client)
    'client_id'   => isset($clients[(int) ($_GET['client'] ?? 0)]) ? (int) $_GET['client'] : null,
    'date_prevue' => '',
    'atelier_id'  => array_key_first($ateliers),
    'reference'   => '',
    'colis'       => [],
    'pieces'      => [['numero' => '', 'designation' => '', 'masse' => '', 'etat' => 'a_faire']],
];

$client     = $commande['client_id'] !== null ? ($clients[$commande['client_id']] ?? null) : null;
$datePrevue = $commande['date_prevue'] !== '' ? new DateTimeImmutable($commande['date_prevue']) : null;
$atelier    = $ateliers[$commande['atelier_id']] ?? '';

$etatsPiece = [
    'a_faire'   => 'À faire',
    'en_cours'  => 'En cours de fabrication',
    'fabriquee' => 'Fabriquée',
    'livree'    => 'Livrée',
];

// Pastilles du récapitulatif : [singulier, pluriel, classe d'étiquette, icône]
$pastillesEtat = [
    'fabriquee' => ['fabriquée', 'fabriquées', 'etiquette--prete',    'coche'],
    'en_cours'  => ['en cours',  'en cours',   'etiquette--en-cours', 'horloge'],
    'a_faire'   => ['à faire',   'à faire',    'etiquette--a-faire',  'cercle'],
    'livree'    => ['livrée',    'livrées',    'etiquette--livree',   'coche'],
];

$piecesSaisies = array_filter($commande['pieces'], fn ($p) => $p['numero'] !== '');
$nbPieces    = count($piecesSaisies);
$masseTotale = array_sum(array_map('intval', array_column($piecesSaisies, 'masse')));
$nbParEtat   = array_count_values(array_column($piecesSaisies, 'etat'));
$nbColis     = count($commande['colis']);

$titre = $modification ? 'Modifier la commande ' . $commande['numero'] : 'Nouvelle commande';
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <!-- Seules les ressources du site sont autorisées (aucun CDN, aucune police externe) -->
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; img-src 'self' data:; object-src 'none'; base-uri 'self'; form-action 'self'">
    <meta name="referrer" content="no-referrer">
    <title><?= e($titre) ?> · T.G.V Atelier</title>

    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/commun.css">
    <link rel="stylesheet" href="css/pages/commande.css">
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
                        <a href="liste-commandes.php">Commandes</a>
                        <?= icone('chevron-droite', 18) ?>
                        <span aria-current="page"><?= e($modification ? $commande['numero'] : 'Nouvelle commande') ?></span>
                    </nav>
                    <h1 class="titre-l"><?= e($titre) ?></h1>
                    <p class="texte-m texte-secondaire">
                        <?php if ($modification): ?>
                            <?= e($client['nom']) ?> · prévue <?= e(date_fr($datePrevue)) ?>
                        <?php else: ?>
                            Choisissez le client, la date de livraison puis saisissez les pièces
                        <?php endif; ?>
                    </p>
                </div>
                <div class="entete-page__actions">
                    <a class="btn btn-texte btn-moyen" href="liste-commandes.php">Annuler</a>
                    <button class="btn btn-primary btn-moyen" type="submit" form="formulaire-commande"><?= icone('coche', 20) ?>Enregistrer</button>
                </div>
            </div>

            <div class="grille-commande">

                <form class="grille-commande__formulaire" id="formulaire-commande" action="commande.php<?= $modification ? '?id=' . $id : '' ?>" method="post">

                    <!-- Informations -->
                    <section class="carte" aria-labelledby="titre-informations">
                        <h2 class="titre-m" id="titre-informations">Informations</h2>

                        <div class="champs-commande">
                            <div class="champ">
                                <label class="champ__libelle" for="client">Client</label>
                                <select class="champ__saisie" id="client" name="client_id" required>
                                    <?php if (!$client): ?>
                                        <option value="" selected disabled>Choisir un client</option>
                                    <?php endif; ?>
                                    <?php foreach ($clients as $clientId => $c): ?>
                                        <option value="<?= $clientId ?>"<?= $clientId === $commande['client_id'] ? ' selected' : '' ?>><?= e($c['nom']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="champ__icone"><?= icone('chevron-bas') ?></span>
                            </div>

                            <div class="champ">
                                <label class="champ__libelle" for="date-prevue">Date de livraison prévue</label>
                                <input class="champ__saisie" id="date-prevue" name="date_prevue" type="date" value="<?= e($commande['date_prevue']) ?>" required>
                            </div>

                            <div class="champ">
                                <label class="champ__libelle" for="atelier">Atelier de fabrication</label>
                                <select class="champ__saisie" id="atelier" name="atelier_id">
                                    <?php foreach ($ateliers as $atelierId => $nom): ?>
                                        <option value="<?= $atelierId ?>"<?= $atelierId === $commande['atelier_id'] ? ' selected' : '' ?>><?= e($nom) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="champ__icone"><?= icone('chevron-bas') ?></span>
                            </div>

                            <div class="champ">
                                <label class="champ__libelle" for="reference">Référence client (facultatif)</label>
                                <input class="champ__saisie" id="reference" name="reference" type="text" value="<?= e($commande['reference']) ?>" placeholder="Ex. bon de commande du client">
                            </div>
                        </div>

                        <p class="adresse-livraison texte-m texte-secondaire">
                            <?= icone('adresse', 20) ?>
                            <?= $client ? 'Adresse de livraison : ' . e($client['adresse']) : 'L’adresse de livraison s’affichera une fois le client choisi.' ?>
                        </p>
                    </section>

                    <!-- Pièces -->
                    <section class="carte" aria-labelledby="titre-pieces">
                        <div class="carte__entete">
                            <h2 class="titre-m" id="titre-pieces">Pièces de la commande</h2>
                            <span class="texte-m texte-secondaire"><?= e(pluriel($nbPieces, 'pièce', 'pièces')) ?> · <?= e(nombre($masseTotale)) ?> kg</span>
                        </div>

                        <div class="pieces">
                            <?php foreach ($commande['pieces'] as $i => $piece): ?>
                                <div class="piece">
                                    <div class="champ piece__numero">
                                        <label class="champ__libelle" for="piece-<?= $i ?>-numero">N° de pièce</label>
                                        <input class="champ__saisie" id="piece-<?= $i ?>-numero" name="pieces[<?= $i ?>][numero]" type="text" value="<?= e($piece['numero']) ?>" required>
                                    </div>
                                    <div class="champ piece__designation">
                                        <label class="champ__libelle" for="piece-<?= $i ?>-designation">Désignation</label>
                                        <input class="champ__saisie" id="piece-<?= $i ?>-designation" name="pieces[<?= $i ?>][designation]" type="text" value="<?= e($piece['designation']) ?>">
                                    </div>
                                    <div class="champ piece__masse">
                                        <label class="champ__libelle" for="piece-<?= $i ?>-masse">Masse (kg)</label>
                                        <input class="champ__saisie" id="piece-<?= $i ?>-masse" name="pieces[<?= $i ?>][masse]" type="number" min="0" step="1" value="<?= e($piece['masse']) ?>" required>
                                    </div>
                                    <div class="champ piece__etat">
                                        <label class="champ__libelle" for="piece-<?= $i ?>-etat">État</label>
                                        <select class="champ__saisie" id="piece-<?= $i ?>-etat" name="pieces[<?= $i ?>][etat]">
                                            <?php foreach ($etatsPiece as $cle => $libelle): ?>
                                                <option value="<?= $cle ?>"<?= $cle === $piece['etat'] ? ' selected' : '' ?>><?= e($libelle) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <span class="champ__icone"><?= icone('chevron-bas') ?></span>
                                    </div>
                                    <button class="bouton-icone piece__supprimer" type="button" aria-label="<?= $piece['numero'] !== '' ? 'Supprimer la pièce ' . e($piece['numero']) : 'Supprimer cette pièce' ?>" title="Supprimer la pièce"><?= icone('supprimer', 22) ?></button>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <button class="btn btn-tonal btn-moyen align-self-start" type="button"><?= icone('plus', 20) ?>Ajouter une pièce</button>
                    </section>

                    <!-- Suppression -->
                    <?php if ($modification): ?>
                        <section class="carte" aria-labelledby="titre-suppression">
                            <div class="zone-suppression">
                                <div class="d-flex flex-column gap-1">
                                    <h3 class="titre-s" id="titre-suppression">Supprimer la commande</h3>
                                    <p class="texte-m texte-secondaire">
                                        <?= $nbPieces > 1 ? 'Les ' . $nbPieces . ' pièces' : 'La pièce' ?>
                                        et <?= $nbColis > 1 ? 'les colis associés' : 'le colis associé' ?>
                                        seront retirés de la tournée du <?= e(date_fr($datePrevue)) ?>.
                                    </p>
                                </div>
                                <button class="btn btn-contour-erreur btn-moyen" type="button"><?= icone('supprimer', 20) ?>Supprimer la commande</button>
                            </div>
                        </section>
                    <?php endif; ?>

                </form>

                <!-- Récapitulatif -->
                <aside class="carte carte--tonale recapitulatif" aria-labelledby="titre-recapitulatif">
                    <h2 class="titre-m" id="titre-recapitulatif">Récapitulatif</h2>

                    <div class="tuiles tuiles--2">
                        <div class="tuile">
                            <span class="tuile__valeur"><?= e(nombre($nbPieces)) ?></span>
                            <span class="tuile__libelle"><?= $nbPieces > 1 ? 'pièces' : 'pièce' ?></span>
                        </div>
                        <div class="tuile">
                            <span class="tuile__valeur"><?= e(nombre($masseTotale)) ?></span>
                            <span class="tuile__libelle">kg au total</span>
                        </div>
                    </div>

                    <div class="recapitulatif__bloc">
                        <h3 class="titre-s">État des pièces</h3>
                        <?php if ($nbParEtat): ?>
                            <div class="d-flex flex-wrap gap-2">
                                <?php foreach ($pastillesEtat as $cle => [$singulier, $plurielEtat, $classe, $nomIcone]): ?>
                                    <?php if (!empty($nbParEtat[$cle])): ?>
                                        <span class="etiquette <?= $classe ?>"><?= icone($nomIcone, 16, 2.2) ?><?= $nbParEtat[$cle] ?> <?= $nbParEtat[$cle] > 1 ? $plurielEtat : $singulier ?></span>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="texte-m texte-secondaire">Aucune pièce saisie.</p>
                        <?php endif; ?>
                    </div>

                    <div class="recapitulatif__bloc">
                        <h3 class="titre-s">Emballage</h3>
                        <p class="texte-m texte-secondaire">
                            <?php if ($nbColis === 0): ?>
                                Le colis sera créé à l’enregistrement.
                            <?php elseif ($nbColis === 1): ?>
                                1 colis de <?= e(nombre($commande['colis'][0])) ?> kg pour ce client.
                            <?php else: ?>
                                <?= $nbColis ?> colis : <?= e(implode(' kg, ', array_map('nombre', $commande['colis']))) ?> kg.
                            <?php endif; ?>
                        </p>
                        <?php if ($nbColis > 0): ?>
                            <button class="btn btn-texte btn-moyen" type="button">Séparer en plusieurs colis</button>
                        <?php endif; ?>
                    </div>

                    <div class="recapitulatif__bloc">
                        <h3 class="titre-s">Livraison</h3>
                        <p class="texte-m texte-secondaire">
                            <?php if ($datePrevue): ?>
                                Tournée du <?= e(date_fr($datePrevue)) ?> · départ <?= e($atelier) ?> · camion à affecter par le chauffeur.
                            <?php else: ?>
                                Choisissez une date pour rattacher la commande à une tournée.
                            <?php endif; ?>
                        </p>
                    </div>
                </aside>

            </div>

        </main>
    </div>
</div>
</body>
</html>
