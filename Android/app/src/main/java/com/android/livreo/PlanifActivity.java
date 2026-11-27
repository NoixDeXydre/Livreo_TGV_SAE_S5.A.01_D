package com.android.livreo;

import android.os.Bundle;

import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.Toolbar;
import androidx.viewpager2.widget.ViewPager2;

import com.android.livreo.planif.AdaptateurOngletsPlanif;
import com.google.android.material.tabs.TabLayout;
import com.google.android.material.tabs.TabLayoutMediator;

/**
 * Écrans planifier la tournée, en 2 onglets (Manuel et Optimisé).
 */
public class PlanifActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_planif);
        Toolbar toolbar = findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        setTitle(R.string.titre_planifier);
        // Flèche retour dans la Toolbar (gérée par onSupportNavigateUp)
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);

        String[] titreOnglet = {
                getString(R.string.onglet_manuel),
                getString(R.string.onglet_optimise)
        };

        ViewPager2 gestionnairePagination = findViewById(R.id.pagination);
        TabLayout gestionnaireOnglet = findViewById(R.id.onglets);
        gestionnairePagination.setAdapter(new AdaptateurOngletsPlanif(this));
        new TabLayoutMediator(gestionnaireOnglet, gestionnairePagination,
                (tab, position) -> tab.setText(titreOnglet[position])
        ).attach();
    }

    @Override
    public boolean onSupportNavigateUp() {
        finish();
        return true;
    }
}
