package fr.tgv.api.service;

import fr.tgv.api.model.Client;
import fr.tgv.api.repository.ClientRepository;
import org.springframework.stereotype.Service;

import java.util.List;

/**
 * Fait la passerelle entre la base de donnée et l'appelant de l'API
 * pour les opérations concernant les clients
 */
@Service
public class ClientService {

    /** Répertoire qui permet de communiquer avec la collection client */
    private final ClientRepository clientRepository;

    /**
     * Crée un service
     * @param clientRepository répertoire des clients
     */
    public ClientService(ClientRepository clientRepository) {
        this.clientRepository = clientRepository;
    }

    /**
     * Récupère la liste de tous les clients
     * @return la liste des clients
     */
    public List<Client> getAllClients() {
        return clientRepository.findAll();
    }

    /**
     * Récupère un client en fonction de son id
     * @param id l'id du client
     * @return Le client s'il le trouve, null sinon
     */
    public Client getClientById(String id) {
        return clientRepository.findById(id).orElse(null);
    }

    /**
     * Crée un nouveau client
     * @param client Les données du client
     * @return Le client créé
     * @throws IllegalArgumentException si le client à créer est null
     */
    public Client createClient(Client client) {
        if (client == null) {
            throw new IllegalArgumentException("Le client est invalide");
        }

        return clientRepository.save(client);
    }

    /**
     * Met à jour un client existant
     * @param id l'id du client
     * @param client Les données du client à modifier
     * @return Le client modifié ou null s'il n'est pas trouvé
     * @throws IllegalArgumentException si le client modifié est null
     */
    public Client updateClient(String id, Client client) {
        if (client == null) {
            throw new IllegalArgumentException("Le client est invalide");
        }

        /* On vérifie que l'utilisateur existe */
        if (clientRepository.existsById(id)) {
            return null;
        }

        return clientRepository.save(client);
    }

    /**
     * Supprime un client existant
     * @param id
     * @return vrai si la suppression a été effectuée, faux sinon
     */
    public boolean deleteClient(String id) {
        /* On vérifie que l'utilisateur existe */
        if (clientRepository.existsById(id)) {
            /* Sinon, on renvoie une erreur */
            clientRepository.deleteById(id);
            return true;
        } else {
            return false;
        }
    }
}