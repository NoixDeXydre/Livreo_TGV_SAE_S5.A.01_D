package fr.tgv.api.repository;

import fr.tgv.api.model.Colis;
import org.springframework.data.mongodb.repository.MongoRepository;

public interface ColisRepository extends MongoRepository<Colis, String> {
}