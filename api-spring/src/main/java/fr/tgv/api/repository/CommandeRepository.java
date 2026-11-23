package fr.tgv.api.repository;

import fr.tgv.api.model.Commande;
import org.springframework.data.mongodb.repository.MongoRepository;

public interface CommandeRepository extends MongoRepository<Commande, String> {
}