package fr.tgv.api.model;

import org.springframework.data.annotation.Id;
import org.springframework.data.mongodb.core.mapping.Document;

@Document(collection = "pieces")
public class Piece {

    @Id
    private String id;

    private double poids;
    private String etat;

    public Piece() {
    }

    public Piece(String id, double poids, String etat) {
        this.id = id;
        this.poids = poids;
        this.etat = etat;
    }

    public String getId() {
        return id;
    }

    public void setId(String id) {
        this.id = id;
    }

    public double getPoids() {
        return poids;
    }

    public void setPoids(double poids) {
        this.poids = poids;
    }

    public String getEtat() {
        return etat;
    }

    public void setEtat(String etat) {
        this.etat = etat;
    }
}