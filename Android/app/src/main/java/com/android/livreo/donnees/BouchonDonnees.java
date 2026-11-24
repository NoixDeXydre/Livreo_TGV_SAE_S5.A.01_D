package com.android.livreo.donnees;

import com.android.livreo.modele.Client;
import com.android.livreo.modele.Colis;
import com.android.livreo.modele.Tournee;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

/**
 * Bouchon : données des maquettes, en dur, pour afficher les vues.
 */
public final class BouchonDonnees {

    /** true = afficher les écrans dans leur version hors ligne (maquette 02b). */
    public static final boolean HORS_LIGNE = false;

    /** PLANIFIEE = le bouton "Démarrer la tournée" devient actif. */
    public static final Tournee.Etat ETAT_TOURNEE = Tournee.Etat.A_PLANIFIER;

    private BouchonDonnees() {
    }

    public static Tournee chargerTournee() {
        Client combes = new Client("Scierie Combes", "8 route du Moulin");
        Client bastide = new Client("Chaudronnerie Bastide", "27 avenue de l’Industrie");
        Client dupuy = new Client("Métallerie Dupuy", "14 rue des Forges");
        Client fabre = new Client("Garage Fabre", "2 place de la Gare");
        Client maury = new Client("Serres Maury", "3 chemin des Serres");

        List<Colis> colis = new ArrayList<>();
        colis.add(new Colis("C-01", combes, 4, 820));
        colis.add(new Colis("C-02", combes, 2, 760));
        colis.add(new Colis("C-03", combes, 3, 700));
        colis.add(new Colis("C-04", bastide, 1, 640));
        colis.add(new Colis("C-05", bastide, 2, 590));
        colis.add(new Colis("C-06", dupuy, 3, 510));
        colis.add(new Colis("C-07", dupuy, 2, 470));
        colis.add(new Colis("C-08", fabre, 1, 350));
        colis.add(new Colis("C-09", maury, 1, 85));

        return new Tournee(
                "Tournée du jeudi 1 octobre",
                "jeudi 1 oct. à 7 h 42",
                "7 h 42",
                Arrays.asList(combes, bastide, dupuy, fabre, maury),
                colis,
                ETAT_TOURNEE);
    }
}