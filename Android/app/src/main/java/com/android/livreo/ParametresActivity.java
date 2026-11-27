package com.android.livreo;

import android.content.Intent;
import android.os.Bundle;

import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.Toolbar;

/**
 * Vue seule pour l'instant : le contenu est en dur dans le layout,
 * et le résultat du test de connexion est un bouchon.
 */
public class ParametresActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_parametres);
        Toolbar toolbar = findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        setTitle(R.string.titre_parametres);
        // Flèche retour dans la Toolbar (gérée par onSupportNavigateUp)
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);

        findViewById(R.id.boutonTester).setOnClickListener(v -> afficherTestConnexion());
        findViewById(R.id.boutonDeconnecter).setOnClickListener(v -> seDeconnecter());
    }

    /** Boîte de dialogue de la maquette, avec un résultat en dur. */
    private void afficherTestConnexion() {
        new AlertDialog.Builder(this)
                .setTitle(R.string.test_connexion_titre)
                .setMessage(R.string.bouchon_test_connexion)
                .setPositiveButton(android.R.string.ok, null)
                .show();
    }

    /** Retour à la connexion, en vidant la pile pour ne pas revenir à l'accueil. */
    private void seDeconnecter() {
        Intent intention = new Intent(this, ConnexionActivity.class);
        intention.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
        startActivity(intention);
    }

    @Override
    public boolean onSupportNavigateUp() {
        finish();
        return true;
    }
}
