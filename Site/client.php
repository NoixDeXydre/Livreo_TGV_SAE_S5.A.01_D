<?php
require __DIR__ . '/includes/fonctions.php';

$pageActive = 'clients';

/* =========================================================
   Données d'exemple
   Elles seront remplacées par les lectures en base ; la vue
   n'utilise que les variables ci-dessous.
   Sans ?id= (ou avec un id inconnu) : formulaire de création.
   ========================================================= */
$aujourdhui = new DateTimeImmutable('2026-10-05');

$clientsExemple = [
    1 => [
        'nom' => 'Scierie Combes', 'contact' => 'Mme Combes', 'telephone' => '05 61 51 20 14',
        'adresse' => '8 route du Moulin', 'code_postal' => '31600', 'ville' => 'Muret', 'adresse_verifiee' => true,
        'commandes_a_livrer' => ['CMD-115'],
    ],
    2 => [
        'nom' => 'Chaudronnerie Bastide', 'contact' => 'M. Bastide', 'telephone' => '05 61 78 42 90',
        'adresse' => '27 avenue de l’Industrie', 'code_postal' => '31770', 'ville' => 'Colomiers', 'adresse_verifiee' => true,
        'commandes_a_livrer' => ['CMD-116'],
    ],
    3 => [
        'nom' => 'Métallerie Dupuy', 'contact' => 'M. Dupuy', 'telephone' => '',
        'adresse' => '14 rue des Forges', 'code_postal' => '31700', 'ville' => 'Blagnac', 'adresse_verifiee' => true,
        'commandes_a_livrer' => ['CMD-117', 'CMD-121'],
    ],
    4 => [
        'nom' => 'Garage Fabre', 'contact' => 'M. Fabre', 'telephone' => '05 61 24 66 03',
        'adresse' => '5 zone artisanale des Prés', 'code_postal' => '31130', 'ville' => 'Balma', 'adresse_verifiee' => true,
        'commandes_a_livrer' => ['CMD-118'],
    ],
    5 => [
        'nom' => 'Serres Maury', 'contact' => 'Mme Maury', 'telephone' => '05 61 39 08 57',
        'adresse' => '3 chemin des Serres', 'code_postal' => '31320', 'ville' => 'Castanet-Tolosan', 'adresse_verifiee' => true,
        'commandes_a_livrer' => ['CMD-119', 'CMD-120'],
    ],
];


/* =========================================================
   Préparation de l'affichage
   ========================================================= */
$id = (int) ($_GET['id'] ?? 0);
$modification = isset($clientsExemple[$id]);

$client = $clientsExemple[$id] ?? [
    'nom' => '', 'contact' => '', 'telephone' => '',
    'adresse' => '', 'code_postal' => '', 'ville' => '', 'adresse_verifiee' => false,
    'commandes_a_livrer' => [],
];

$adresseSaisie   = $client['adresse'] !== '';
$adresseComplete = trim($client['adresse'] . ', ' . $client['code_postal'] . ' ' . $client['ville'], ', ');
$nbALivrer       = count($client['commandes_a_livrer']);

$titre      = $modification ? 'Modifier le client' : 'Nouveau client';
$urlRetour  = $modification ? 'liste-clients.php?id=' . $id : 'liste-clients.php';
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <!-- Seules les ressources du site sont autorisées (aucun CDN, aucune police externe) -->
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; img-src 'self' data:; object-src 'none'; base-uri 'self'; form-action 'self'">
    <meta name="referrer" content="no-referrer">
    <title><?= e($modification ? $titre . ' ' . $client['nom'] : $titre) ?> · T.G.V Atelier</title>

    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/commun.css">
    <link rel="stylesheet" href="css/pages/client.css">
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
                        <a href="liste-clients.php">Clients</a>
                        <?= icone('chevron-droite', 18) ?>
                        <span aria-current="page"><?= e($modification ? $client['nom'] : 'Nouveau client') ?></span>
                    </nav>
                    <h1 class="titre-l"><?= e($titre) ?></h1>
                    <p class="texte-m texte-secondaire">
                        <?= $modification ? e($client['nom']) : 'Saisissez l’identité du client et son adresse de livraison' ?>
                    </p>
                </div>
                <div class="entete-page__actions">
                    <a class="btn btn-texte btn-moyen" href="<?= e($urlRetour) ?>">Annuler</a>
                    <button class="btn btn-primary btn-moyen" type="submit" form="formulaire-client"><?= icone('coche', 20) ?>Enregistrer</button>
                </div>
            </div>

            <div class="grille-client">

                <form class="grille-client__formulaire" id="formulaire-client" action="client.php<?= $modification ? '?id=' . $id : '' ?>" method="post">

                    <!-- Identité -->
                    <section class="carte" aria-labelledby="titre-identite">
                        <h2 class="titre-m" id="titre-identite">Identité</h2>

                        <div class="champs-identite">
                            <div class="champ">
                                <label class="champ__libelle" for="nom">Raison sociale</label>
                                <input class="champ__saisie" id="nom" name="nom" type="text" value="<?= e($client['nom']) ?>" required>
                            </div>

                            <div class="champ">
                                <label class="champ__libelle" for="contact">Nom du contact</label>
                                <input class="champ__saisie" id="contact" name="contact" type="text" value="<?= e($client['contact']) ?>">
                            </div>

                            <div class="champ">
                                <label class="champ__libelle" for="telephone">Téléphone du contact</label>
                                <input class="champ__saisie" id="telephone" name="telephone" type="tel" value="<?= e($client['telephone']) ?>" placeholder="05 00 00 00 00">
                            </div>
                        </div>
                    </section>

                    <!-- Adresse de livraison -->
                    <section class="carte" aria-labelledby="titre-adresse">
                        <div class="d-flex flex-column gap-1">
                            <h2 class="titre-m" id="titre-adresse">Adresse de livraison</h2>
                            <p class="texte-m texte-secondaire">Un client n’a qu’une seule adresse de livraison.</p>
                        </div>

                        <div class="champs-adresse">
                            <div class="champ">
                                <label class="champ__libelle" for="adresse">Adresse</label>
                                <input class="champ__saisie" id="adresse" name="adresse" type="text" value="<?= e($client['adresse']) ?>" required>
                            </div>

                            <div class="champs-adresse__ville">
                                <div class="champ">
                                    <label class="champ__libelle" for="code-postal">Code postal</label>
                                    <input class="champ__saisie" id="code-postal" name="code_postal" type="text" inputmode="numeric" pattern="[0-9]{5}" maxlength="5" value="<?= e($client['code_postal']) ?>" required>
                                </div>

                                <div class="champ">
                                    <label class="champ__libelle" for="ville">Ville</label>
                                    <input class="champ__saisie" id="ville" name="ville" type="text" value="<?= e($client['ville']) ?>" required>
                                </div>
                            </div>
                        </div>

                        <button class="btn btn-tonal btn-moyen align-self-start" type="button"><?= icone('adresse', 20) ?>Vérifier l’adresse</button>
                    </section>

                </form>

                <!-- Aperçu de l'adresse -->
                <aside class="carte" aria-labelledby="titre-apercu">
                    <h2 class="titre-m" id="titre-apercu">Aperçu de l’adresse</h2>

                    <?php if ($adresseSaisie): ?>
                        <div class="bloc-adresse">
                            <span class="bloc-adresse__icone"><?= icone('adresse', 26) ?></span>
                            <span class="d-flex flex-column">
                                <span class="titre-s"><?= e($client['nom']) ?></span>
                                <span class="texte-m texte-secondaire"><?= e($adresseComplete) ?></span>
                            </span>
                        </div>

                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <?php if ($client['adresse_verifiee']): ?>
                                <span class="etiquette etiquette--livree"><?= icone('coche', 16, 2.2) ?>Adresse vérifiée</span>
                                <span class="texte-m texte-secondaire">Utilisée pour calculer la boucle de livraison.</span>
                            <?php else: ?>
                                <span class="etiquette etiquette--en-cours"><?= icone('alerte', 16, 2.2) ?>Adresse à vérifier</span>
                                <span class="texte-m texte-secondaire">Elle doit être vérifiée avant le calcul de la boucle.</span>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <p class="texte-m texte-secondaire">L’aperçu s’affichera une fois l’adresse saisie, puis vérifiée avant le calcul de la boucle de livraison.</p>
                    <?php endif; ?>
                </aside>

            </div>

            <!-- Suppression : bloquée tant que des commandes restent à livrer -->
            <?php if ($modification): ?>
                <section class="carte" aria-labelledby="titre-suppression">
                    <div class="zone-suppression">
                        <div class="zone-suppression__texte">
                            <h3 class="titre-s" id="titre-suppression">Supprimer le client</h3>
                            <p class="texte-m texte-secondaire">
                                <?php if ($nbALivrer > 0): ?>
                                    Ce client a <?= e(pluriel($nbALivrer, 'commande', 'commandes')) ?> à livrer (<?= e(implode(', ', $client['commandes_a_livrer'])) ?>).
                                    <?= $nbALivrer > 1 ? 'Supprimez-les ou rattachez-les' : 'Supprimez-la ou rattachez-la' ?> à un autre client avant de continuer.
                                <?php else: ?>
                                    Ce client n’a aucune commande à livrer. Ses commandes livrées restent dans l’historique.
                                <?php endif; ?>
                            </p>
                        </div>
                        <button class="btn btn-contour-erreur btn-moyen" type="button"<?= $nbALivrer > 0 ? ' disabled' : '' ?>><?= icone('supprimer', 20) ?>Supprimer le client</button>
                    </div>
                </section>
            <?php endif; ?>

        </main>
    </div>
</div>
</body>
</html>
