package com.android.livreo.modele;

import java.util.List;

public class Tournee {

    public enum Etat {
        A_PLANIFIER("À planifier"),
        PLANIFIEE("Planifiée"),
        EN_COURS("En cours"),
        TERMINEE("Terminée");

        private final String libelle;

        Etat(String libelle) {
            this.libelle = libelle;
        }

        public String getLibelle() {
            return libelle;
        }
    }

    private final String libelle;       // ex. "Tournée du jeudi 1 octobre"
    private final String dateSynchro;   // ex. "jeudi 1 oct. à 7 h 42"
    private final String heureSynchro;  // ex. "7 h 42"
    private final List<Client> clients;
    private final List<Colis> colis;
    private Etat etat;

    public Tournee(String libelle, String dateSynchro, String heureSynchro,
                   List<Client> clients, List<Colis> colis, Etat etat) {
        this.libelle = libelle;
        this.dateSynchro = dateSynchro;
        this.heureSynchro = heureSynchro;
        this.clients = clients;
        this.colis = colis;
        this.etat = etat;
    }

    public int getPoidsTotalKg() {
        int total = 0;
        for (Colis c : colis) {
            total += c.getPoidsKg();
        }
        return total;
    }

    public int getNbColis() {
        return colis.size();
    }

    public int getNbClients() {
        return clients.size();
    }

    public String getLibelle() {
        return libelle;
    }

    public String getDateSynchro() {
        return dateSynchro;
    }

    public String getHeureSynchro() {
        return heureSynchro;
    }

    public List<Client> getClients() {
        return clients;
    }

    public List<Colis> getColis() {
        return colis;
    }

    public Etat getEtat() {
        return etat;
    }

    public void setEtat(Etat etat) {
        this.etat = etat;
    }
}