<?php
/* =========================================================
   Barre latérale commune aux pages de l'application
   Attend : $pageActive (clé de la page courante, ex. 'tableau-de-bord')
   ========================================================= */

// Données d'exemple : viendront de la session et de la base
$utilisateur = [
    'identifiant' => 'responsable01',
    'role'        => 'Responsable des commandes',
    'initiales'   => 'RC',
];
$atelierCourant = 'Atelier principal';

$liensPrincipaux = [
    'tableau-de-bord' => ['Tableau de bord', 'tableau-de-bord.php', 'tableau-de-bord'],
    'commandes'       => ['Commandes',       'liste-commandes.php', 'commandes'],
    'fabrication'     => ['Fabrication',     'fabrication.php',     'fabrication'],
    'clients'         => ['Clients',         'clients.php',         'clients'],
    'livraisons'      => ['Livraisons',      'livraisons.php',      'livraisons'],
    'boucles'         => ['Boucles',         'boucles.php',         'boucles'],
];
$liensOrganisation = [
    'ressources'      => ['Ressources',      'ressources.php',      'atelier'],
];

$lienNavigation = function (string $cle, array $lien) use ($pageActive): string {
    [$libelle, $url, $nomIcone] = $lien;
    $courant = $cle === $pageActive ? ' aria-current="page"' : '';

    return '<a class="navigation__lien" href="' . e($url) . '"' . $courant . ' title="' . e($libelle) . '">'
         . icone($nomIcone)
         . '<span class="replie-masque">' . e($libelle) . '</span>'
         . '</a>';
};
?>
<aside class="barre-laterale">
    <div class="barre-laterale__interieur">

        <a class="marque" href="tableau-de-bord.php" aria-label="T.G.V Atelier — accueil">
            <span class="pastille-icone pastille-icone--petite"><?= icone('camion') ?></span>
            <span class="marque__texte replie-masque">
                <span class="marque__nom">T.G.V Atelier</span>
                <span class="texte-s texte-secondaire">Commandes et livraisons</span>
            </span>
        </a>

        <button class="selecteur-atelier" type="button" aria-label="Changer d’atelier : <?= e($atelierCourant) ?>" title="<?= e($atelierCourant) ?>">
            <span class="texte-secondaire"><?= icone('atelier', 22) ?></span>
            <span class="selecteur-atelier__texte replie-masque">
                <span class="texte-s texte-secondaire">Atelier</span>
                <span class="selecteur-atelier__nom"><?= e($atelierCourant) ?></span>
            </span>
            <span class="texte-secondaire replie-masque"><?= icone('chevron-bas', 20) ?></span>
        </button>

        <nav class="navigation" aria-label="Navigation principale">
            <?php foreach ($liensPrincipaux as $cle => $lien): ?>
                <?= $lienNavigation($cle, $lien) ?>
            <?php endforeach; ?>

            <span class="navigation__titre replie-masque">Organisation</span>

            <?php foreach ($liensOrganisation as $cle => $lien): ?>
                <?= $lienNavigation($cle, $lien) ?>
            <?php endforeach; ?>
        </nav>

        <div class="profil">
            <span class="profil__avatar" aria-hidden="true"><?= e($utilisateur['initiales']) ?></span>
            <span class="profil__texte replie-masque">
                <span class="profil__nom"><?= e($utilisateur['identifiant']) ?></span>
                <span class="texte-s texte-secondaire"><?= e($utilisateur['role']) ?></span>
            </span>
            <a class="bouton-icone replie-masque" href="connexion.html" aria-label="Se déconnecter" title="Se déconnecter"><?= icone('deconnexion', 22) ?></a>
        </div>

    </div>
</aside>
