package com.android.livreo.planif;

import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Toast;

import androidx.fragment.app.Fragment;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.android.livreo.R;
import com.android.livreo.donnees.BouchonDonnees;

/**
 * Onglet Manuel : le chauffeur choisit l'ordre de passage chez les clients.
 * La distance affichée est celle de l'ordre initial (bouchon), elle n'est pas recalculée.
 */
public class ManuelFragment extends Fragment {

    public static ManuelFragment newInstance() {
        return new ManuelFragment();
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        View vue = inflater.inflate(R.layout.fragment_manuel, container, false);

        RecyclerView liste = vue.findViewById(R.id.listeArrets);
        liste.setLayoutManager(new LinearLayoutManager(getContext()));
        liste.setAdapter(new ArretAdapter(BouchonDonnees.chargerTournee()));

        // Écran Résultat (10) pas encore fait
        vue.findViewById(R.id.boutonValider).setOnClickListener(v ->
                Toast.makeText(getContext(), R.string.ecran_a_venir, Toast.LENGTH_SHORT).show());

        return vue;
    }
}
