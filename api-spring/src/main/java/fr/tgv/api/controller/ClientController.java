package fr.tgv.api.controller;

import fr.tgv.api.model.Client;
import fr.tgv.api.service.ClientService;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;
import org.springframework.web.servlet.support.ServletUriComponentsBuilder;

import java.net.URI;
import java.util.List;

/**
 * Controleur REST permettant la gestion des clients
 */
@RestController
@RequestMapping("/clients")
public class ClientController {

    /**
     * Service métier permettant la gestion des clients.
     */
    private final ClientService clientService;

    /**
     * Constructeur du controleur de l'api des clients
     * @param clientService Service utilisé pour accéder aux données
     *                      des clients
     */
    public ClientController(ClientService clientService) {
        this.clientService = clientService;
    }

    /**
     * Récupère la liste des clients
     * @return la liste des clients ou une liste vide s'il n'y en a pas
     */
    @GetMapping
    public ResponseEntity<List<Client>> getAllClients() {
        return ResponseEntity.ok(clientService.getAllClients());
    }

    /**
     * Récupère un client en fonction de son id
     * @param id l'id du client à récupérer
     * @return le client et un code
     *              - 200 si le client est trouvé
     *              - 400 s'il n'est pas trouvé
     */
    @GetMapping("/{id}")
    public ResponseEntity<Client> getClientById(@PathVariable String id) {
        Client client = clientService.getClientById(id);

        if (client != null) {
            return ResponseEntity.ok(client);
        } else {
            return ResponseEntity.notFound().build();
        }
    }

    /**
     * Crée un nouveau client
     * @param client le client à créer
     * @return Le client créé et un code
     *              - 201 le client a bien été créé
     *              - 400 si la requête est erronée
     */
    @PutMapping("/addClient")
    public ResponseEntity<Client> createClient(@RequestBody Client client) {
        try {
            Client clientCree = clientService.createClient(client);

            URI location = ServletUriComponentsBuilder
                    .fromCurrentRequest()
                    .buildAndExpand(clientCree.getId())
                    .toUri();

            return ResponseEntity.created(location).build();
        } catch (IllegalArgumentException e) {
            return ResponseEntity.badRequest().build();
        }
    }

    /**
     * Modifie un client
     * @param id l'id de la randonnée à modifier
     * @param client le client modifié
     * @return Le client et un code
     *              - 200 si la modification a été effectuée
     *              - 404 si le client à modifier n'as pas été trouvé
     *              - 400 si la requête est erronée
     */
    @PatchMapping("/updateClient/{id}")
    public ResponseEntity<Client> updateClient(@PathVariable String id, @RequestBody Client client) {
        try {
            Client clientModifie = clientService.updateClient(id, client);

            if (client == null) {
                return ResponseEntity.notFound().build();
            }

            return ResponseEntity.ok(clientModifie);
        } catch (IllegalArgumentException e) {
            return ResponseEntity.badRequest().build();
        }
    }

    /**
     * Supprime un client
     *
     * @param id l'id du client à modifier
     * @return Un code
     *              - 204 si le client a bien été supprimé
     *              - 404 si le client n'as pas été trouvé
     */
    @DeleteMapping("/deleteClient/{id}")
    public ResponseEntity<Client> deleteClient(@PathVariable String id) {
        if(clientService.deleteClient(id)) {
            return ResponseEntity.noContent().build();
        } else {
            return ResponseEntity.notFound().build();
        }
    }
}