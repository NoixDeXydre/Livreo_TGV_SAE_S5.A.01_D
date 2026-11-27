package com.android.livreo.planif;

import android.content.Context;
import android.view.View;
import android.widget.ImageButton;
import android.widget.TextView;

import androidx.recyclerview.widget.RecyclerView;

import com.android.livreo.R;
import com.android.livreo.modele.Client;

/**
 * Contenant d'un arrêt de la liste (vue item_arret.xml).
 */
public class ArretViewHolder extends RecyclerView.ViewHolder {

    private final TextView texteNumero;
    private final TextView texteNom;
    private final TextView texteResume;
    private final ImageButton boutonMonter;
    private final ImageButton boutonDescendre;

    public ArretViewHolder(View itemView) {
        super(itemView);
        texteNumero = itemView.findViewById(R.id.texteNumero);
        texteNom = itemView.findViewById(R.id.texteNom);
        texteResume = itemView.findViewById(R.id.texteResume);
        boutonMonter = itemView.findViewById(R.id.boutonMonter);
        boutonDescendre = itemView.findViewById(R.id.boutonDescendre);
    }

    /**
     * Place les informations de l'arrêt dans les widgets de l'item.
     * Le premier arrêt ne peut pas monter, le dernier ne peut pas descendre.
     */
    public void bind(Client client, String resume, int position, int nbArrets,
                     View.OnClickListener monter, View.OnClickListener descendre) {
        Context contexte = itemView.getContext();
        texteNumero.setText(String.valueOf(position + 1));
        texteNom.setText(client.getNom());
        texteResume.setText(resume);

        boutonMonter.setContentDescription(contexte.getString(R.string.monter_arret, client.getNom()));
        boutonDescendre.setContentDescription(contexte.getString(R.string.descendre_arret, client.getNom()));
        activer(boutonMonter, position > 0);
        activer(boutonDescendre, position < nbArrets - 1);
        boutonMonter.setOnClickListener(monter);
        boutonDescendre.setOnClickListener(descendre);
    }

    /** Un bouton désactivé est grisé (25 % d'opacité, comme sur la maquette). */
    private void activer(ImageButton bouton, boolean actif) {
        bouton.setEnabled(actif);
        bouton.setAlpha(actif ? 1f : 0.25f);
    }
}
