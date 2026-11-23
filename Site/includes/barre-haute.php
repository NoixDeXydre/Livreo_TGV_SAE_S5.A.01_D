<?php
/* =========================================================
   Barre du haut commune : recherche globale et date du jour
   Sur mobile, elle reprend aussi le choix d'atelier et la
   déconnexion, absents de la barre d'onglets du bas.
   Attend : $aujourdhui (DateTimeInterface)
   ========================================================= */

require_once __DIR__ . '/utilisateur.php';
?>
<header class="barre-haute">
    <form class="recherche" role="search" action="#" method="get">
        <span class="recherche__icone"><?= icone('recherche') ?></span>
        <input class="recherche__saisie" type="search" name="q"
               aria-label="Rechercher une commande, un client ou une pièce"
               placeholder="Rechercher une commande, un client, une pièce">
    </form>
    <span class="barre-haute__date texte-m texte-secondaire"><?= e(date_fr($aujourdhui, true, true)) ?></span>

    <div class="barre-haute__mobile">
        <button class="bouton-icone" type="button" aria-label="Changer d’atelier : <?= e($atelierCourant) ?>" title="<?= e($atelierCourant) ?>"><?= icone('atelier', 22) ?></button>
        <a class="bouton-icone" href="connexion.html" aria-label="Se déconnecter (<?= e($utilisateur['identifiant']) ?>)" title="Se déconnecter"><?= icone('deconnexion', 22) ?></a>
    </div>
</header>
