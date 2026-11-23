<?php
/* =========================================================
   Barre du haut commune : recherche globale et date du jour
   Attend : $aujourdhui (DateTimeInterface)
   ========================================================= */
?>
<header class="barre-haute">
    <form class="recherche" role="search" action="#" method="get">
        <span class="recherche__icone"><?= icone('recherche') ?></span>
        <input class="recherche__saisie" type="search" name="q"
               aria-label="Rechercher une commande, un client ou une pièce"
               placeholder="Rechercher une commande, un client, une pièce">
    </form>
    <span class="barre-haute__date texte-m texte-secondaire"><?= e(date_fr($aujourdhui, true, true)) ?></span>
</header>
