package com.android.livreo.planif;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;

import androidx.recyclerview.widget.RecyclerView;

import com.android.livreo.R;
import com.android.livreo.modele.Client;
import com.android.livreo.modele.Tournee;

import java.util.Collections;
import java.util.List;
import java.util.Locale;

/**
 * Adaptateur de la liste des arrêts (RecyclerView).
 */
public class ArretAdapter extends RecyclerView.Adapter<ArretViewHolder> {

    private final List<Client> lesArrets;
    /** Sert à calculer le nombre et le poids des colis de chaque arrêt. */
    private final Tournee tournee;

    public ArretAdapter(Tournee tournee) {
        this.tournee = tournee;
        lesArrets = tournee.getClients();
    }

    @Override
    public ArretViewHolder onCreateViewHolder(ViewGroup parent, int itemType) {
        View vue = LayoutInflater.from(parent.getContext())
                .inflate(R.layout.item_arret, parent, false);
        return new ArretViewHolder(vue);
    }

    @Override
    public void onBindViewHolder(ArretViewHolder holder, int position) {
        Client client = lesArrets.get(position);
        String resume = holder.itemView.getContext().getString(R.string.resume_client,
                tournee.getNbColisPour(client),
                String.format(Locale.FRANCE, "%,d", tournee.getPoidsKgPour(client)));
        holder.bind(client, resume, position, lesArrets.size(),
                v -> echanger(holder.getBindingAdapterPosition(), -1),
                v -> echanger(holder.getBindingAdapterPosition(), 1));
    }

    @Override
    public int getItemCount() {
        return lesArrets.size();
    }

    /** Échange l'arrêt avec celui du dessus (sens = -1) ou du dessous (sens = 1). */
    private void echanger(int position, int sens) {
        int voisin = position + sens;
        if (position == RecyclerView.NO_POSITION || voisin < 0 || voisin >= lesArrets.size()) {
            return;
        }
        Collections.swap(lesArrets, position, voisin);
        // Redessiner les 2 arrêts échangés (contenu, numéro et boutons grisés)
        notifyItemRangeChanged(Math.min(position, voisin), 2);
    }
}
