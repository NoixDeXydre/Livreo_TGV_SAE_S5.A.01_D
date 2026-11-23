<?php
require __DIR__ . '/includes/fonctions.php';

$pageActive = 'clients';

/* =========================================================
   Données d'exemple
   Elles seront remplacées par les lectures en base ; la vue
   n'utilise que les variables ci-dessous.
   ========================================================= */
$aujourdhui = new DateTimeImmutable('2026-10-05');

// etat d'une commande : a_faire | en_fabrication | prete | livree
$clients = [
    1 => [
        'nom' => 'Scierie Combes', 'adresse' => '8 route du Moulin', 'ville' => '31600 Muret',
        'contact' => 'Mme Combes', 'adresse_verifiee' => true,
        'commandes' => [
            ['id' => 115, 'numero' => 'CMD-115', 'date' => '2026-10-08', 'pieces' => 2, 'masse' => 640,  'etat' => 'en_fabrication'],
            ['id' => 110, 'numero' => 'CMD-110', 'date' => '2026-10-01', 'pieces' => 9, 'masse' => 2280, 'etat' => 'livree'],
        ],
    ],
    2 => [
        'nom' => 'Chaudronnerie Bastide', 'adresse' => '27 avenue de l’Industrie', 'ville' => '31770 Colomiers',
        'contact' => 'M. Bastide', 'adresse_verifiee' => true,
        'commandes' => [
            ['id' => 116, 'numero' => 'CMD-116', 'date' => '2026-10-08', 'pieces' => 3, 'masse' => 1150, 'etat' => 'en_fabrication'],
            ['id' => 111, 'numero' => 'CMD-111', 'date' => '2026-10-01', 'pieces' => 3, 'masse' => 1230, 'etat' => 'livree'],
        ],
    ],
    3 => [
        'nom' => 'Métallerie Dupuy', 'adresse' => '14 rue des Forges', 'ville' => '31700 Blagnac',
        'contact' => 'M. Dupuy', 'adresse_verifiee' => true,
        'commandes' => [
            ['id' => 117, 'numero' => 'CMD-117', 'date' => '2026-10-08', 'pieces' => 4, 'masse' => 720, 'etat' => 'en_fabrication'],
            ['id' => 121, 'numero' => 'CMD-121', 'date' => '2026-10-15', 'pieces' => 1, 'masse' => 150, 'etat' => 'a_faire'],
            ['id' => 114, 'numero' => 'CMD-114', 'date' => '2026-10-01', 'pieces' => 5, 'masse' => 980, 'etat' => 'livree'],
        ],
    ],
    4 => [
        'nom' => 'Garage Fabre', 'adresse' => '5 zone artisanale des Prés', 'ville' => '31130 Balma',
        'contact' => 'M. Fabre', 'adresse_verifiee' => true,
        'commandes' => [
            ['id' => 118, 'numero' => 'CMD-118', 'date' => '2026-10-08', 'pieces' => 2, 'masse' => 350, 'etat' => 'prete'],
            ['id' => 112, 'numero' => 'CMD-112', 'date' => '2026-10-01', 'pieces' => 1, 'masse' => 350, 'etat' => 'livree'],
        ],
    ],
    5 => [
        'nom' => 'Serres Maury', 'adresse' => '3 chemin des Serres', 'ville' => '31320 Castanet-Tolosan',
        'contact' => 'Mme Maury', 'adresse_verifiee' => true,
        'commandes' => [
            ['id' => 119, 'numero' => 'CMD-119', 'date' => '2026-10-08', 'pieces' => 1, 'masse' => 85,  'etat' => 'prete'],
            ['id' => 120, 'numero' => 'CMD-120', 'date' => '2026-10-15', 'pieces' => 3, 'masse' => 240, 'etat' => 'a_faire'],
            ['id' => 113, 'numero' => 'CMD-113', 'date' => '2026-10-01', 'pieces' => 1, 'masse' => 85,  'etat' => 'livree'],
        ],
    ],
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

// Client affiché (?id=) : le premier de la liste par défaut
$idActif = isset($clients[(int) ($_GET['id'] ?? 0)]) ? (int) $_GET['id'] : array_key_first($clients);
$client  = $clients[$idActif];

$aLivrer    = fn (array $c) => array_filter($c['commandes'], fn ($cmd) => $cmd['etat'] !== 'livree');
$historique = array_filter($client['commandes'], fn ($cmd) => $cmd['etat'] === 'livree');

$ligneCommande = function (array $commande) use ($etatsCommande): string {
    [$libelle, $classe] = $etatsCommande[$commande['etat']];

    return '<li class="ligne-commande">'
         . '<a class="ligne-commande__numero" href="commande.php?id=' . e($commande['id']) . '">' . e($commande['numero']) . '</a>'
         . '<span class="ligne-commande__details texte-m texte-secondaire">'
         . e(date_courte(new DateTimeImmutable($commande['date']))) . ' · '
         . e(pluriel($commande['pieces'], 'pièce', 'pièces')) . ' · '
         . e(nombre($commande['masse'])) . ' kg</span>'
         . '<span class="etiquette ' . $classe . '">' . e($libelle) . '</span>'
         . '</li>';
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
    <title>Clients · T.G.V Atelier</title>

    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/commun.css">
    <link rel="stylesheet" href="css/pages/liste-clients.css">
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
                    <h1 class="titre-l">Clients</h1>
                    <p class="texte-m texte-secondaire"><?= e(pluriel(count($clients), 'client', 'clients')) ?> · une adresse de livraison chacun</p>
                </div>
                <div class="entete-page__actions">
                    <a class="btn btn-primary btn-moyen" href="client.php"><?= icone('plus', 20) ?>Nouveau client</a>
                </div>
            </div>

            <div class="grille-clients">

                <!-- Liste des clients -->
                <nav class="liste-clients" aria-label="Liste des clients">
                    <?php foreach ($clients as $id => $c): ?>
                        <a class="liste-clients__element" href="liste-clients.php?id=<?= $id ?>"<?= $id === $idActif ? ' aria-current="true"' : '' ?>>
                            <span class="avatar" aria-hidden="true"><?= e(initiales($c['nom'])) ?></span>
                            <span class="liste-clients__texte">
                                <span class="titre-s"><?= e($c['nom']) ?></span>
                                <span class="texte-m texte-secondaire"><?= e($c['adresse']) ?></span>
                            </span>
                            <span class="liste-clients__nombre"><?= count($aLivrer($c)) ?> à livrer</span>
                        </a>
                    <?php endforeach; ?>
                </nav>

                <!-- Fiche du client sélectionné -->
                <section class="fiche-client" aria-labelledby="titre-client">
                    <div class="fiche-client__entete">
                        <div class="fiche-client__identite">
                            <span class="avatar avatar--grand" aria-hidden="true"><?= e(initiales($client['nom'])) ?></span>
                            <div class="d-flex flex-column min-w-0">
                                <h2 class="titre-l" id="titre-client"><?= e($client['nom']) ?></h2>
                                <span class="texte-m texte-secondaire"><?= e($client['adresse']) ?> · <?= e($client['ville']) ?></span>
                            </div>
                        </div>
                        <div class="carte__actions">
                            <a class="btn btn-contour btn-moyen" href="client.php?id=<?= $idActif ?>"><?= icone('modifier', 20) ?>Modifier</a>
                            <a class="btn btn-primary btn-moyen" href="commande.php?client=<?= $idActif ?>"><?= icone('plus', 20) ?>Nouvelle commande</a>
                        </div>
                    </div>

                    <div class="fiche-client__grille">
                        <section class="carte" aria-labelledby="titre-adresse">
                            <h3 class="titre-m" id="titre-adresse">Adresse de livraison</h3>
                            <div class="bloc-adresse">
                                <span class="bloc-adresse__icone"><?= icone('adresse') ?></span>
                                <span class="d-flex flex-column min-w-0">
                                    <span class="titre-s"><?= e($client['adresse']) ?></span>
                                    <span class="texte-m texte-secondaire"><?= e($client['ville']) ?></span>
                                </span>
                            </div>
                            <?php if ($client['adresse_verifiee']): ?>
                                <span class="etiquette etiquette--livree"><?= icone('coche', 16, 2.2) ?>Adresse vérifiée</span>
                            <?php else: ?>
                                <span class="etiquette etiquette--en-cours"><?= icone('alerte', 16, 2.2) ?>Adresse à vérifier</span>
                            <?php endif; ?>
                            <p class="texte-s texte-secondaire">Un client n’a qu’une seule adresse de livraison.</p>
                        </section>

                        <section class="carte" aria-labelledby="titre-contact">
                            <h3 class="titre-m" id="titre-contact">Contact</h3>
                            <div class="contact">
                                <span class="texte-secondaire"><?= icone('personne') ?></span>
                                <span class="d-flex flex-column">
                                    <span><?= e($client['contact']) ?></span>
                                    <span class="texte-s texte-secondaire">Interlocuteur pour les livraisons</span>
                                </span>
                            </div>
                        </section>
                    </div>

                    <section class="carte carte--commandes" aria-labelledby="titre-commandes-client">
                        <h3 class="titre-m" id="titre-commandes-client">Commandes</h3>

                        <h4 class="sous-titre-commandes">À livrer</h4>
                        <?php if ($aLivrer($client)): ?>
                            <ul class="liste-commandes-client">
                                <?php foreach ($aLivrer($client) as $commande): ?>
                                    <?= $ligneCommande($commande) ?>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="liste-commandes-client__vide texte-m texte-secondaire">Aucune commande en attente de livraison.</p>
                        <?php endif; ?>

                        <h4 class="sous-titre-commandes">Historique</h4>
                        <?php if ($historique): ?>
                            <ul class="liste-commandes-client">
                                <?php foreach ($historique as $commande): ?>
                                    <?= $ligneCommande($commande) ?>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="liste-commandes-client__vide texte-m texte-secondaire">Aucune commande livrée pour l’instant.</p>
                        <?php endif; ?>
                    </section>
                </section>

            </div>

        </main>
    </div>
</div>
</body>
</html>
