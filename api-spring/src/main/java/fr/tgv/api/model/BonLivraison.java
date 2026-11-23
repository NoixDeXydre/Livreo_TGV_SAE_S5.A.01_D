package fr.tgv.api.model;

import org.springframework.data.annotation.Id;
import org.springframework.data.mongodb.core.mapping.Document;

import java.time.LocalDate;

@Document(collection = "bons_livraison")
public class BonLivraison {

    @Id
    private String id;

    private String colisId;
    private LocalDate dateLivraison;
    private boolean valide;

    public BonLivraison() {
    }

    public BonLivraison(String id, String colisId, LocalDate dateLivraison, boolean valide) {
        this.id = id;
        this.colisId = colisId;
        this.dateLivraison = dateLivraison;
        this.valide = valide;
    }

    public String getId() {
        return id;
    }

    public void setId(String id) {
        this.id = id;
    }

    public String getColisId() {
        return colisId;
    }

    public void setColisId(String colisId) {
        this.colisId = colisId;
    }

    public LocalDate getDateLivraison() {
        return dateLivraison;
    }

    public void setDateLivraison(LocalDate dateLivraison) {
        this.dateLivraison = dateLivraison;
    }

    public boolean isValide() {
        return valide;
    }

    public void setValide(boolean valide) {
        this.valide = valide;
    }
}