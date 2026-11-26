package com.android.livreo.colis;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;

import androidx.recyclerview.widget.RecyclerView;

import com.android.livreo.R;
import com.android.livreo.modele.Colis;

import java.util.List;

/**
 * Adaptateur de la liste des colis (RecyclerView).
 */
public class ColisAdapter extends RecyclerView.Adapter<ColisViewHolder> {

    private final List<Colis> lesColis;

    public ColisAdapter(List<Colis> colis) {
        lesColis = colis;
    }

    @Override
    public ColisViewHolder onCreateViewHolder(ViewGroup parent, int itemType) {
        View vue = LayoutInflater.from(parent.getContext())
                .inflate(R.layout.item_colis, parent, false);
        return new ColisViewHolder(vue);
    }

    @Override
    public void onBindViewHolder(ColisViewHolder holder, int position) {
        holder.bind(lesColis.get(position));
    }

    @Override
    public int getItemCount() {
        return lesColis.size();
    }
}
