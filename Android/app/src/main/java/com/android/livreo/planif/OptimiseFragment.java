package com.android.livreo.planif;

import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.RadioButton;
import android.widget.RadioGroup;
import android.widget.Spinner;

import androidx.appcompat.app.AlertDialog;
import androidx.fragment.app.Fragment;
import androidx.viewpager2.widget.ViewPager2;

import com.android.livreo.R;
import com.android.livreo.donnees.BouchonDonnees;

/**
 * Onglet Optimisé : choix de l'algorithme et lancement du calcul (fait par le serveur).
 * Hors ligne (BouchonDonnees.HORS_LIGNE), le calcul est désactivé (maquette 08b).
 */
public class OptimiseFragment extends Fragment {

    private Spinner spinnerDepart;
    private RadioGroup groupeAlgorithmes;

    public static OptimiseFragment newInstance() {
        return new OptimiseFragment();
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        View vue = inflater.inflate(R.layout.fragment_optimise, container, false);
        spinnerDepart = vue.findViewById(R.id.spinnerDepart);
        groupeAlgorithmes = vue.findViewById(R.id.groupeAlgorithmes);

        vue.findViewById(R.id.boutonLancer).setOnClickListener(v -> afficherCalcul());

        if (BouchonDonnees.HORS_LIGNE) {
            afficherHorsLigne(vue);
        }
        return vue;
    }

    /** Maquette 09 : boîte affichée pendant le calcul (pas encore de serveur). */
    private void afficherCalcul() {
        RadioButton algoChoisi = groupeAlgorithmes.findViewById(
                groupeAlgorithmes.getCheckedRadioButtonId());
        new AlertDialog.Builder(requireContext())
                .setTitle(R.string.calcul_titre)
                .setMessage(getString(R.string.calcul_message,
                        algoChoisi.getText(), spinnerDepart.getSelectedItem()))
                .setNegativeButton(android.R.string.cancel, null)
                .show();
    }

    /** Maquette 08b : bandeau, algorithmes et calcul désactivés, bouton vers le manuel. */
    private void afficherHorsLigne(View vue) {
        vue.findViewById(R.id.bandeauHorsLigne).setVisibility(View.VISIBLE);
        vue.findViewById(R.id.texteEnLigne).setVisibility(View.GONE);
        for (int i = 0; i < groupeAlgorithmes.getChildCount(); i++) {
            groupeAlgorithmes.getChildAt(i).setEnabled(false);
        }
        vue.findViewById(R.id.boutonLancer).setEnabled(false);

        View boutonModeManuel = vue.findViewById(R.id.boutonModeManuel);
        boutonModeManuel.setVisibility(View.VISIBLE);
        boutonModeManuel.setOnClickListener(v -> {
            ViewPager2 pagination = requireActivity().findViewById(R.id.pagination);
            pagination.setCurrentItem(0);
        });
    }
}
