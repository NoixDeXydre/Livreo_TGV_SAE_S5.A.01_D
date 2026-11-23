package fr.tgv.api.model;

import org.springframework.data.annotation.Id;
import org.springframework.data.mongodb.core.mapping.Document;

@Document(collection = "clients")
public class Client {

    @Id
    private String id;

    private String nom;
    private String adresse;
    private Double latitude;
    private Double longitude;

    public Client() {
    }

    public Client(String id, String nom, String adresse, String longitude,
                  String latitude) {
        if (nom.isBlank()) {
            throw new IllegalArgumentException("Le nom ne peut pas être vide");
        }

        if (adresse.isBlank()) {
            throw new IllegalArgumentException("L'adresse ne peut pas être vide");
        }

        this.id = id;
        this.nom = nom;
        this.adresse = adresse;
        try {
            this.longitude = Double.parseDouble(longitude);
            this.latitude = Double.parseDouble(latitude);
        } catch (NumberFormatException e) {
            throw new IllegalArgumentException("Les coordonnées sont incorrectes");
        }
    }

    public String getId() {
        return id;
    }

    public void setId(String id) {
        this.id = id;
    }

    public String getNom() {
        return nom;
    }

    public void setNom(String nom) {
        this.nom = nom;
    }

    public String getAdresse() {
        return adresse;
    }

    public void setAdresse(String adresse) {
        this.adresse = adresse;
    }

    public Double getLatitude() {
        return latitude;
    }

    public void setLatitude(Double latitude) {
        this.latitude = latitude;
    }

    public Double getLongitude() {
        return longitude;
    }

    public void setLongitude(Double longitude) {
        this.longitude = longitude;
    }
}