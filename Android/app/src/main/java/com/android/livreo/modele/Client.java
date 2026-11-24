package com.android.livreo.modele;

public class Client {

    private final String nom;
    private final String adresse;

    public Client(String nom, String adresse) {
        this.nom = nom;
        this.adresse = adresse;
    }

    public String getNom() {
        return nom;
    }

    public String getAdresse() {
        return adresse;
    }
}