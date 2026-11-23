package fr.tgv.api.model;

import org.springframework.data.annotation.Id;
import org.springframework.data.mongodb.core.mapping.Document;

import java.time.LocalDate;
import java.util.List;

@Document(collection = "tournees")
public class Tournee {

    @Id
    private String id;

    private LocalDate date;

    private List<String> colisIds;

    private List<String> ordreClientsIds;

    private double distanceKm;

    public Tournee() {
    }

    public Tournee(String id, LocalDate date, List<String> colisIds,
                   List<String> ordreClientsIds, double distanceKm) {
        this.id = id;
        this.date = date;
        this.colisIds = colisIds;
        this.ordreClientsIds = ordreClientsIds;
        this.distanceKm = distanceKm;
    }

    public String getId() {
        return id;
    }

    public void setId(String id) {
        this.id = id;
    }

    public LocalDate getDate() {
        return date;
    }

    public void setDate(LocalDate date) {
        this.date = date;
    }

    public List<String> getColisIds() {
        return colisIds;
    }

    public void setColisIds(List<String> colisIds) {
        this.colisIds = colisIds;
    }

    public List<String> getOrdreClientsIds() {
        return ordreClientsIds;
    }

    public void setOrdreClientsIds(List<String> ordreClientsIds) {
        this.ordreClientsIds = ordreClientsIds;
    }

    public double getDistanceKm() {
        return distanceKm;
    }

    public void setDistanceKm(double distanceKm) {
        this.distanceKm = distanceKm;
    }
}