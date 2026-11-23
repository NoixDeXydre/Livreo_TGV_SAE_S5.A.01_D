package fr.tgv.api.service;

import fr.tgv.api.model.Commande;
import fr.tgv.api.repository.CommandeRepository;
import org.springframework.stereotype.Service;

import java.util.List;

@Service
public class CommandeService {

    private final CommandeRepository commandeRepository;

    public CommandeService(CommandeRepository commandeRepository) {
        this.commandeRepository = commandeRepository;
    }

    public List<Commande> getAllCommandes() {
        return commandeRepository.findAll();
    }

    public Commande getCommandeById(String id) {
        return commandeRepository.findById(id).orElse(null);
    }

    public Commande createCommande(Commande commande) {
        return commandeRepository.save(commande);
    }

    public Commande updateCommande(String id, Commande commande) {
        commande.setId(id);
        return commandeRepository.save(commande);
    }

    public void deleteCommande(String id) {
        commandeRepository.deleteById(id);
    }
}