package fr.tgv.api.service;

import fr.tgv.api.model.Piece;
import fr.tgv.api.repository.PieceRepository;
import org.springframework.stereotype.Service;

import java.util.List;

/**
 * Fait la passerelle entre la base de donnée et l'appelant de l'API
 * pour les opérations concernant les pièces
 */
@Service
public class PieceService {

    /** Répertoire qui permet de communiquer avec la collection pièce */
    private final PieceRepository pieceRepository;

    /**
     * Crée un service
     * @param pieceRepository répertoire des pièces
     */
    public PieceService(PieceRepository pieceRepository) {
        this.pieceRepository = pieceRepository;
    }

    /**
     * Récupère la liste de toutes les pièces
     * @return la liste des pièces
     */
    public List<Piece> getAllPieces() {
        return pieceRepository.findAll();
    }

    /**
     * Récupère une pièce en fonction de son id
     * @param id l'id de la pièce
     * @return La pièce s'il le trouve, null sinon
     */
    public Piece getPieceById(String id) {
        return pieceRepository.findById(id).orElse(null);
    }

    /**
     * Crée une nouvelle pièce
     * @param piece Les données de la pièce
     * @return La pièce créée
     * @throws IllegalArgumentException si la pièce à créer est null
     */
    public Piece createPiece(Piece piece) {
        if (piece == null) {
            throw new IllegalArgumentException("Le pièce est invalide");
        }

        return pieceRepository.save(piece);
    }

    /**
     * Met à jour une pièce existant
     * @param id l'id de la pièce
     * @param piece Les données de la pièce à modifier
     * @return La pièce modifiée ou null s'il n'est pas trouvé
     * @throws IllegalArgumentException si le pièce modifié est null
     */
    public Piece updatePiece(String id, Piece piece) {
        if (piece == null) {
            throw new IllegalArgumentException("La pièce est invalide");
        }

        /* On vérifie que l'utilisateur existe */
        if (pieceRepository.existsById(id)) {
            return null;
        }

        return pieceRepository.save(piece);
    }

    /**
     * Supprime une pièce existante
     * @param id
     * @return vrai si la suppression a été effectuée, faux sinon
     */
    public boolean deletePiece(String id) {
        /* On vérifie que la pièce existe */
        if (pieceRepository.existsById(id)) {
            /* Sinon, on renvoie une erreur */
            pieceRepository.deleteById(id);
            return true;
        } else {
            return false;
        }
    }
}