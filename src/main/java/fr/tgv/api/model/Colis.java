package fr.tgv.api.model;

import org.springframework.data.annotation.Id;
import org.springframework.data.mongodb.core.mapping.Document;

import java.util.List;

@Document(collection = "colis")
public class Colis {

    @Id
    private String id;

    private String commandeId;
    private String clientId;
    private List<String> piecesIds;

    public Colis() {
    }

    public Colis(String id, String commandeId, String clientId, List<String> piecesIds) {
        this.id = id;
        this.commandeId = commandeId;
        this.clientId = clientId;
        this.piecesIds = piecesIds;
    }

    public String getId() {
        return id;
    }

    public void setId(String id) {
        this.id = id;
    }

    public String getCommandeId() {
        return commandeId;
    }

    public void setCommandeId(String commandeId) {
        this.commandeId = commandeId;
    }

    public String getClientId() {
        return clientId;
    }

    public void setClientId(String clientId) {
        this.clientId = clientId;
    }

    public List<String> getPiecesIds() {
        return piecesIds;
    }

    public void setPiecesIds(List<String> piecesIds) {
        this.piecesIds = piecesIds;
    }
}