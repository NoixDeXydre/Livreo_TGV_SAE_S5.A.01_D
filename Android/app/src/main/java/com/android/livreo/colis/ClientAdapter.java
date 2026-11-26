package com.android.livreo.colis;

import android.content.ActivityNotFoundException;
import android.content.Context;
import android.content.Intent;
import android.net.Uri;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.ImageButton;
import android.widget.TextView;
import android.widget.Toast;

import com.android.livreo.R;
import com.android.livreo.modele.Client;
import com.android.livreo.modele.Tournee;

import java.util.Locale;

/**
 * Adaptateur personnalisé de la liste des clients (ListView).
 * Chaque item affiche le nom, l'adresse, le résumé des colis et un bouton carte.
 */
public class ClientAdapter extends ArrayAdapter<Client> {

    /** Sert à calculer le nombre et le poids des colis de chaque client. */
    private final Tournee tournee;

    public ClientAdapter(Context contexte, Tournee tournee) {
        super(contexte, R.layout.item_client, tournee.getClients());
        this.tournee = tournee;
    }

    @Override
    public View getView(int position, View uneVue, ViewGroup parent) {
        if (uneVue == null) {
            uneVue = LayoutInflater.from(getContext()).inflate(R.layout.item_client, parent, false);
        }
        Client client = getItem(position);

        ((TextView) uneVue.findViewById(R.id.texteNom)).setText(client.getNom());
        ((TextView) uneVue.findViewById(R.id.texteAdresse)).setText(client.getAdresse());
        ((TextView) uneVue.findViewById(R.id.texteResume)).setText(getContext().getString(
                R.string.resume_client,
                tournee.getNbColisPour(client),
                String.format(Locale.FRANCE, "%,d", tournee.getPoidsKgPour(client))));

        ImageButton boutonCarte = uneVue.findViewById(R.id.boutonCarte);
        boutonCarte.setContentDescription(
                getContext().getString(R.string.voir_sur_carte, client.getNom()));
        boutonCarte.setOnClickListener(v -> ouvrirCarte(client.getAdresse()));

        return uneVue;
    }

    /** Intention implicite : l'appli de cartes du téléphone affiche l'adresse. */
    private void ouvrirCarte(String adresse) {
        Intent intention = new Intent(Intent.ACTION_VIEW,
                Uri.parse("geo:0,0?q=" + Uri.encode(adresse)));
        try {
            getContext().startActivity(intention);
        } catch (ActivityNotFoundException e) {
            Toast.makeText(getContext(), R.string.aucune_appli_carte, Toast.LENGTH_SHORT).show();
        }
    }
}
