package com.android.livreo.colis;

import androidx.fragment.app.Fragment;
import androidx.fragment.app.FragmentActivity;
import androidx.viewpager2.adapter.FragmentStateAdapter;

/**
 * Fait le lien entre le ViewPager2 de ColisActivity et ses 2 onglets :
 * position 0 = Colis, position 1 = Clients.
 */
public class AdaptateurOngletsColis extends FragmentStateAdapter {

    private static final int NB_FRAGMENT = 2;

    public AdaptateurOngletsColis(FragmentActivity activite) {
        super(activite);
    }

    @Override
    public Fragment createFragment(int position) {
        if (position == 1) {
            return ClientsFragment.newInstance();
        }
        return ColisFragment.newInstance();
    }

    @Override
    public int getItemCount() {
        return NB_FRAGMENT;
    }
}
