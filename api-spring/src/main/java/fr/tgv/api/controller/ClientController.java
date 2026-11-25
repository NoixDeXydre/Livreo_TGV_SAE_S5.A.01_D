package fr.tgv.api.controller;

import fr.tgv.api.model.Client;
import fr.tgv.api.service.ClientService;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;
import org.springframework.web.servlet.support.ServletUriComponentsBuilder;

import java.net.URI;
import java.util.List;

@RestController
@RequestMapping("/clients")
public class ClientController {

    private final ClientService clientService;

    public ClientController(ClientService clientService) {
        this.clientService = clientService;
    }

    @GetMapping
    public ResponseEntity<List<Client>> getAllClients() {
        return ResponseEntity.ok(clientService.getAllClients());
    }

    @GetMapping("/{id}")
    public ResponseEntity<Client> getClientById(@PathVariable String id) {
        Client client = clientService.getClientById(id);

        if (client != null) {
            return ResponseEntity.ok(client);
        } else {
            return ResponseEntity.notFound().build();
        }
    }

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
            return ResponseEntity.notFound().build();
        }
    }

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

    @DeleteMapping("/deleteClient/{id}")
    public void deleteClient(@PathVariable String id) {
        if(clientService.deleteClient(id)) {
            ResponseEntity.noContent();
        } else {
            ResponseEntity.notFound();
        }
    }
}