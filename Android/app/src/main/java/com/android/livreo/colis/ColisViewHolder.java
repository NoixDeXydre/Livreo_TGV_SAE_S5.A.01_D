package com.android.livreo.colis;

import android.content.Intent;
import android.view.View;
import android.widget.TextView;

import androidx.recyclerview.widget.RecyclerView;

import com.android.livreo.DetailColisActivity;
import com.android.livreo.R;
import com.android.livreo.modele.Colis;

import java.util.Locale;

/**
 * Contenant d'un item de la liste des colis (vue item_colis.xml).
 */
public class ColisViewHolder extends RecyclerView.ViewHolder {

    private final TextView texteCode;
    private final TextView texteClient;
    private final TextView textePieces;
    private final TextView textePoids;

    public ColisViewHolder(View itemView) {
        super(itemView);
        texteCode = itemView.findViewById(R.id.texteCode);
        texteClient = itemView.findViewById(R.id.texteClient);
        textePieces = itemView.findViewById(R.id.textePieces);
        textePoids = itemView.findViewById(R.id.textePoids);
    }

    /** Place les informations du colis dans les widgets de l'item. */
    public void bind(Colis colis) {
        texteCode.setText(colis.getCode());
        texteClient.setText(colis.getClient().getNom());
        textePieces.setText(itemView.getResources().getQuantityString(
                R.plurals.nb_pieces, colis.getNbPieces(), colis.getNbPieces()));
        textePoids.setText(itemView.getContext().getString(R.string.poids_kg,
                String.format(Locale.FRANCE, "%,d", colis.getPoidsKg())));

        // Clic sur la carte : ouvre le détail du colis (écran 05)
        itemView.setOnClickListener(v -> v.getContext().startActivity(
                new Intent(v.getContext(), DetailColisActivity.class)));
    }
}
