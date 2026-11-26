package com.android.livreo;

import android.content.Intent;
import android.os.Bundle;
import android.text.Html;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.Toolbar;

import com.android.livreo.donnees.BouchonDonnees;
import com.android.livreo.modele.Tournee;

import java.util.Locale;

public class AccueilActivity extends AppCompatActivity {

    private Tournee tournee;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_accueil);
        Toolbar toolbar = findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);

        tournee = BouchonDonnees.chargerTournee();
        afficherTournee();
        afficherEtatConnexion();

        findViewById(R.id.boutonVoirColis).setOnClickListener(
                v -> ouvrirColis(ColisActivity.ONGLET_COLIS));
        findViewById(R.id.boutonVoirClients).setOnClickListener(
                v -> ouvrirColis(ColisActivity.ONGLET_CLIENTS));
        findViewById(R.id.boutonPlanifier).setOnClickListener(v -> ecranAVenir());
        findViewById(R.id.boutonDemarrer).setOnClickListener(v -> ecranAVenir());
    }

    private void afficherTournee() {
        ((TextView) findViewById(R.id.texteTitre)).setText(tournee.getLibelle());
        ((TextView) findViewById(R.id.texteNbColis)).setText(String.valueOf(tournee.getNbColis()));
        ((TextView) findViewById(R.id.texteNbClients)).setText(String.valueOf(tournee.getNbClients()));
        // %,d avec Locale.FRANCE affiche 4925 sous la forme "4 925"
        ((TextView) findViewById(R.id.textePoids))
                .setText(String.format(Locale.FRANCE, "%,d", tournee.getPoidsTotalKg()));
        ((TextView) findViewById(R.id.texteEtat)).setText(tournee.getEtat().getLibelle());

        Button boutonDemarrer = findViewById(R.id.boutonDemarrer);
        boutonDemarrer.setEnabled(tournee.getEtat() == Tournee.Etat.PLANIFIEE);
    }

    private void afficherEtatConnexion() {
        TextView bandeau = findViewById(R.id.bandeauHorsLigne);
        TextView texteStatut = findViewById(R.id.texteStatut);
        TextView texteAide = findViewById(R.id.texteAide);
        boolean planifiee = tournee.getEtat() == Tournee.Etat.PLANIFIEE;

        if (BouchonDonnees.HORS_LIGNE) {
            bandeau.setVisibility(View.VISIBLE);
            bandeau.setText(Html.fromHtml(
                    getString(R.string.bandeau_hors_ligne, tournee.getDateSynchro()),
                    Html.FROM_HTML_MODE_LEGACY));
            texteStatut.setText(R.string.statut_accueil_hors_ligne);
            texteStatut.setCompoundDrawablesRelativeWithIntrinsicBounds(
                    R.drawable.point_hors_ligne, 0, 0, 0);
            texteAide.setText(R.string.aide_hors_ligne);
            texteAide.setVisibility(View.VISIBLE);
        } else {
            bandeau.setVisibility(View.GONE);
            texteStatut.setText(getString(R.string.statut_en_ligne, tournee.getHeureSynchro()));
            texteStatut.setCompoundDrawablesRelativeWithIntrinsicBounds(
                    R.drawable.point_en_ligne, 0, 0, 0);
            texteAide.setText(R.string.aide_demarrer);
            texteAide.setVisibility(planifiee ? View.GONE : View.VISIBLE);
        }
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        getMenuInflater().inflate(R.menu.menu_accueil, menu);
        return true;
    }

    // Des if plutôt qu'un switch : les R.id ne sont plus des constantes depuis AGP 8
    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        int id = item.getItemId();
        if (id == R.id.action_synchroniser) {
            Toast.makeText(this, R.string.synchro_a_venir, Toast.LENGTH_SHORT).show();
            return true;
        }
        if (id == R.id.action_parametres) {
            ecranAVenir();
            return true;
        }
        if (id == R.id.action_deconnecter) {
            startActivity(new Intent(this, ConnexionActivity.class));
            finish();
            return true;
        }
        return super.onOptionsItemSelected(item);
    }

    /** Ouvre l'écran des colis sur l'onglet demandé (Colis ou Clients). */
    private void ouvrirColis(int onglet) {
        Intent intention = new Intent(this, ColisActivity.class);
        intention.putExtra(ColisActivity.EXTRA_ONGLET, onglet);
        startActivity(intention);
    }

    private void ecranAVenir() {
        Toast.makeText(this, R.string.ecran_a_venir, Toast.LENGTH_SHORT).show();
    }
}