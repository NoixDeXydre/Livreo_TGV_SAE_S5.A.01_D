package com.android.livreo.planif;

import androidx.fragment.app.Fragment;
import androidx.fragment.app.FragmentActivity;
import androidx.viewpager2.adapter.FragmentStateAdapter;

/**
 * Fait le lien entre le ViewPager2 de PlanifActivity et ses 2 onglets :
 * position 0 = Manuel, position 1 = Optimisé.
 */
public class AdaptateurOngletsPlanif extends FragmentStateAdapter {

    private static final int NB_FRAGMENT = 2;

    public AdaptateurOngletsPlanif(FragmentActivity activite) {
        super(activite);
    }

    @Override
    public Fragment createFragment(int position) {
        if (position == 1) {
            return OptimiseFragment.newInstance();
        }
        return ManuelFragment.newInstance();
    }

    @Override
    public int getItemCount() {
        return NB_FRAGMENT;
    }
}
