package fr.tgv.api.repository;

import fr.tgv.api.model.Tournee;
import org.springframework.data.mongodb.repository.MongoRepository;

public interface TourneeRepository extends MongoRepository<Tournee, String> {
}