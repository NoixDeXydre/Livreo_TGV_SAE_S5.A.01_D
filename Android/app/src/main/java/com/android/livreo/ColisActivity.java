package com.android.livreo;

import android.os.Bundle;

import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.Toolbar;
import androidx.viewpager2.widget.ViewPager2;

import com.android.livreo.colis.AdaptateurOngletsColis;
import com.android.livreo.donnees.BouchonDonnees;
import com.android.livreo.modele.Tournee;
import com.google.android.material.tabs.TabLayout;
import com.google.android.material.tabs.TabLayoutMediator;

/**
 * Écrans 03 et 04 : colis à livrer, en 2 onglets (Colis et Clients).
 * L'accueil indique l'onglet à ouvrir via l'extra EXTRA_ONGLET.
 */
public class ColisActivity extends AppCompatActivity {

    public static final String EXTRA_ONGLET = "com.android.livreo.onglet";
    public static final int ONGLET_COLIS = 0;
    public static final int ONGLET_CLIENTS = 1;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_colis);
        Toolbar toolbar = findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        setTitle(R.string.titre_colis);
        // Flèche retour dans la Toolbar (gérée par onSupportNavigateUp)
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);

        Tournee tournee = BouchonDonnees.chargerTournee();
        String[] titreOnglet = {
                getString(R.string.onglet_colis, tournee.getNbColis()),
                getString(R.string.onglet_clients, tournee.getNbClients())
        };

        ViewPager2 gestionnairePagination = findViewById(R.id.pagination);
        TabLayout gestionnaireOnglet = findViewById(R.id.onglets);
        gestionnairePagination.setAdapter(new AdaptateurOngletsColis(this));
        new TabLayoutMediator(gestionnaireOnglet, gestionnairePagination,
                (tab, position) -> tab.setText(titreOnglet[position])
        ).attach();

        gestionnairePagination.setCurrentItem(
                getIntent().getIntExtra(EXTRA_ONGLET, ONGLET_COLIS), false);
    }

    @Override
    public boolean onSupportNavigateUp() {
        finish();
        return true;
    }
}
