package com.android.livreo.colis;

import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;

import androidx.fragment.app.Fragment;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.android.livreo.R;
import com.android.livreo.donnees.BouchonDonnees;

/**
 * Onglet Colis : liste des colis de la tournée, dans l'ordre du bouchon
 * (déjà trié par poids décroissant).
 */
public class ColisFragment extends Fragment {

    public static ColisFragment newInstance() {
        return new ColisFragment();
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        View vue = inflater.inflate(R.layout.fragment_colis, container, false);

        RecyclerView liste = vue.findViewById(R.id.listeColis);
        liste.setLayoutManager(new LinearLayoutManager(getContext()));
        liste.setAdapter(new ColisAdapter(BouchonDonnees.chargerTournee().getColis()));

        return vue;
    }
}
