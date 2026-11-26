package com.android.livreo.colis;

import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ListView;

import androidx.fragment.app.Fragment;

import com.android.livreo.R;
import com.android.livreo.donnees.BouchonDonnees;

/**
 * Onglet Clients : points de livraison de la tournée, puis points de départ.
 */
public class ClientsFragment extends Fragment {

    public static ClientsFragment newInstance() {
        return new ClientsFragment();
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        View vue = inflater.inflate(R.layout.fragment_clients, container, false);

        ListView liste = vue.findViewById(R.id.listeClients);
        // Pied de liste non cliquable (dernier argument à false)
        liste.addFooterView(inflater.inflate(R.layout.pied_points_depart, liste, false), null, false);
        liste.setAdapter(new ClientAdapter(requireContext(), BouchonDonnees.chargerTournee()));

        return vue;
    }
}
