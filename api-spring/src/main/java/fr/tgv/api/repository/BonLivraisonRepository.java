package fr.tgv.api.repository;

import fr.tgv.api.model.BonLivraison;
import org.springframework.data.mongodb.repository.MongoRepository;

public interface BonLivraisonRepository extends MongoRepository<BonLivraison, String> {
}