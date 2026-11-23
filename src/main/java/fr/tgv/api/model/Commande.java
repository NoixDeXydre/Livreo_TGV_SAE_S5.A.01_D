package fr.tgv.api.model;

import org.springframework.data.annotation.Id;
import org.springframework.data.mongodb.core.mapping.Document;

import java.time.LocalDate;

@Document(collection = "commandes")
public class Commande {

    @Id
    private String id;

    private String pieceId;
    private String clientId;
    private double poids;
    private LocalDate datePrevue;

    public Commande() {
    }

    public Commande(String id, String pieceId, String clientId, double poids, LocalDate datePrevue) {
        this.id = id;
        this.pieceId = pieceId;
        this.clientId = clientId;
        this.poids = poids;
        this.datePrevue = datePrevue;
    }

    public String getId() {
        return id;
    }

    public void setId(String id) {
        this.id = id;
    }

    public String getPieceId() {
        return pieceId;
    }

    public void setPieceId(String pieceId) {
        this.pieceId = pieceId;
    }

    public String getClientId() {
        return clientId;
    }

    public void setClientId(String clientId) {
        this.clientId = clientId;
    }

    public double getPoids() {
        return poids;
    }

    public void setPoids(double poids) {
        this.poids = poids;
    }

    public LocalDate getDatePrevue() {
        return datePrevue;
    }

    public void setDatePrevue(LocalDate datePrevue) {
        this.datePrevue = datePrevue;
    }
}