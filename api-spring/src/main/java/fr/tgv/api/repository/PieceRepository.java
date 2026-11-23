package fr.tgv.api.repository;

import fr.tgv.api.model.Piece;
import org.springframework.data.mongodb.repository.MongoRepository;

public interface PieceRepository extends MongoRepository<Piece, String> {
}