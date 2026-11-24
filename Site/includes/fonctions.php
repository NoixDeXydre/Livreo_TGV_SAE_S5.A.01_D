<?php
/* =========================================================
   Fonctions d'affichage communes à toutes les pages
   1. Échappement
   2. Nombres, pluriels, dates
   3. Icônes
   ========================================================= */


/* ---------- 1. Échappement ---------- */
function e(string|int|float $texte): string
{
    return htmlspecialchars((string) $texte, ENT_QUOTES, 'UTF-8');
}


/* ---------- 2. Nombres, pluriels, dates ---------- */
// Séparateur de milliers : espace fine insécable (« 2 945 »)
function nombre(int|float $n): string
{
    return number_format($n, 0, ',', "\u{202F}");
}

function pluriel(int $n, string $singulier, string $pluriel): string
{
    return nombre($n) . ' ' . ($n > 1 ? $pluriel : $singulier);
}

// Initiales des deux premiers mots : « Scierie Combes » → « SC »
function initiales(string $nom): string
{
    $mots = preg_split('/[\s\-]+/u', trim($nom), -1, PREG_SPLIT_NO_EMPTY);

    return mb_strtoupper(implode('', array_map(fn ($m) => mb_substr($m, 0, 1), array_slice($mots, 0, 2))));
}

function pourcentage(int|float $part, int|float $total): int
{
    return $total > 0 ? (int) round($part / $total * 100) : 0;
}

// Sans dépendre de l'extension intl : « jeudi 1er octobre », « Lundi 5 octobre 2026 »
function date_fr(DateTimeInterface $date, bool $avecAnnee = false, bool $majuscule = false): string
{
    $jours = ['dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'];
    $mois  = ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet',
              'août', 'septembre', 'octobre', 'novembre', 'décembre'];

    $jour  = (int) $date->format('j');
    $texte = $jours[(int) $date->format('w')] . ' '
           . ($jour === 1 ? '1er' : $jour) . ' '
           . $mois[(int) $date->format('n') - 1]
           . ($avecAnnee ? ' ' . $date->format('Y') : '');

    return $majuscule ? ucfirst($texte) : $texte;
}

// Forme abrégée pour les tableaux : « Jeu. 8 oct. », « 1er oct. »
function date_courte(DateTimeInterface $date, bool $avecJour = true): string
{
    $jours = ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'];
    $mois  = ['janv.', 'févr.', 'mars', 'avr.', 'mai', 'juin', 'juil.',
              'août', 'sept.', 'oct.', 'nov.', 'déc.'];

    $jour = (int) $date->format('j');

    return ($avecJour ? $jours[(int) $date->format('w')] . ' ' : '')
         . ($jour === 1 ? '1er' : $jour) . ' '
         . $mois[(int) $date->format('n') - 1];
}

// « 08:48 » → « 8 h 48 »
function heure(string $hhmm): string
{
    [$h, $m] = explode(':', $hhmm);

    return (int) $h . ' h ' . $m;
}


/* ---------- 3. Icônes ---------- */
// Icônes au trait (24 × 24) : elles prennent la couleur du texte qui les entoure
function icone(string $nom, int $taille = 24, float $trait = 2): string
{
    static $traces = [
        'camion'         => '<path d="M2 6h12v10H2z"/><path d="M14 9h4.5L22 12.5V16h-8"/><circle cx="6" cy="17.5" r="1.8"/><circle cx="17.5" cy="17.5" r="1.8"/>',
        'atelier'        => '<path d="M3 21V9l9-5 9 5v12"/><path d="M3 21h18"/><path d="M9 21v-7h6v7"/>',
        'chevron-bas'    => '<path d="M6 9l6 6 6-6"/>',
        'chevron-droite' => '<path d="M9 6l6 6-6 6"/>',
        'retour'         => '<path d="M9 14L4 9l5-5"/><path d="M4 9h10a6 6 0 0 1 0 12h-3"/>',
        'adresse'        => '<path d="M12 21s-7-6.2-7-11a7 7 0 0 1 14 0c0 4.8-7 11-7 11z"/><circle cx="12" cy="10" r="2.5"/>',
        'tableau-de-bord'=> '<rect x="3" y="3" width="7" height="9" rx="1"/><rect x="14" y="3" width="7" height="5" rx="1"/><rect x="14" y="12" width="7" height="9" rx="1"/><rect x="3" y="16" width="7" height="5" rx="1"/>',
        'commandes'      => '<rect x="8" y="2" width="8" height="4" rx="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M9 12h6M9 16h4"/>',
        'fabrication'    => '<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>',
        'clients'        => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>',
        'livraisons'     => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M9 15l2 2 4-4"/>',
        'colis'          => '<path d="M21 8l-9-5-9 5 9 5 9-5z"/><path d="M3 8v8l9 5 9-5V8"/><path d="M12 13v8"/>',
        'boucles'        =>'<circle cx="6" cy="19" r="3"/><path d="M9 19h8.5a3.5 3.5 0 0 0 0-7h-11a3.5 3.5 0 0 1 0-7H15"/><circle cx="18" cy="5" r="3"/>',
        'personne'       => '<circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/>',
        'deconnexion'    => '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5M21 12H9"/>',
        'recherche'      => '<circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/>',
        'plus'           => '<path d="M12 5v14M5 12h14"/>',
        'alerte'         => '<path d="M12 3l10 18H2z"/><path d="M12 10v5M12 18h.01"/>',
        'horloge'        => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
        'coche'          => '<path d="M5 12.5l4.5 4.5L19 7"/>',
        'cercle'         => '<circle cx="12" cy="12" r="8"/>',
        'modifier'       => '<path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/>',
        'supprimer'      => '<path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/>',
        // Pastille pleine : le disque prend la couleur du texte, la coche reste blanche
        'valide'         => '<circle cx="12" cy="12" r="10" fill="currentColor" stroke="none"/><path d="M7.5 12.5l3 3 6-6.5" stroke="#FFFFFF" stroke-width="2.2"/>',
    ];

    return sprintf(
        '<svg width="%1$d" height="%1$d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="%2$s" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">%3$s</svg>',
        $taille,
        $trait,
        $traces[$nom] ?? ''
    );
}
