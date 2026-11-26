package com.android.livreo;

import android.content.Intent;
import android.os.Bundle;
import android.widget.TextView;

import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.Toolbar;

import com.android.livreo.donnees.BouchonDonnees;

public class ConnexionActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_connexion);
        Toolbar toolbar = findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);

        // Le layout affiche "Serveur joignable" par défaut
        if (BouchonDonnees.HORS_LIGNE) {
            TextView texteStatut = findViewById(R.id.texteStatut);
            texteStatut.setText(R.string.statut_hors_ligne);
            texteStatut.setCompoundDrawablesRelativeWithIntrinsicBounds(
                    R.drawable.point_hors_ligne, 0, 0, 0);
        }

        // Pas de vérification pour l'instant : on passe directement à l'accueil
        findViewById(R.id.boutonConnexion).setOnClickListener(v -> {
            startActivity(new Intent(this, AccueilActivity.class));
            finish();
        });
    }
}