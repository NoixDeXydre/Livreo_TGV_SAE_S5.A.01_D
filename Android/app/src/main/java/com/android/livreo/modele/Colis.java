package com.android.livreo.modele;

public class Colis {

    private final String code;
    private final Client client;
    private final int nbPieces;
    private final int poidsKg;

    public Colis(String code, Client client, int nbPieces, int poidsKg) {
        this.code = code;
        this.client = client;
        this.nbPieces = nbPieces;
        this.poidsKg = poidsKg;
    }

    public String getCode() {
        return code;
    }

    public Client getClient() {
        return client;
    }

    public int getNbPieces() {
        return nbPieces;
    }

    public int getPoidsKg() {
        return poidsKg;
    }
}