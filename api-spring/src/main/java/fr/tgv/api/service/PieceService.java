package fr.tgv.api.service;

import fr.tgv.api.model.Piece;
import fr.tgv.api.repository.PieceRepository;
import org.springframework.stereotype.Service;

import java.util.List;

@Service
public class PieceService {

    private final PieceRepository pieceRepository;

    public PieceService(PieceRepository pieceRepository) {
        this.pieceRepository = pieceRepository;
    }

    public List<Piece> getAllPieces() {
        return pieceRepository.findAll();
    }

    public Piece getPieceById(String id) {
        return pieceRepository.findById(id).orElse(null);
    }

    public Piece createPiece(Piece piece) {
        return pieceRepository.save(piece);
    }

    public Piece updatePiece(String id, Piece piece) {
        piece.setId(id);
        return pieceRepository.save(piece);
    }

    public void deletePiece(String id) {
        pieceRepository.deleteById(id);
    }
}