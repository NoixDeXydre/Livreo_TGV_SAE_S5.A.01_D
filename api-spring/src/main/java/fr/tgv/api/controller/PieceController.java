package fr.tgv.api.controller;

import fr.tgv.api.model.Client;
import fr.tgv.api.model.Piece;
import fr.tgv.api.service.PieceService;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;
import org.springframework.web.servlet.support.ServletUriComponentsBuilder;

import java.net.URI;
import java.util.List;

/**
 * Controleur REST permettant la gestion des pièces
 */
@RestController
@RequestMapping("/pieces")
public class PieceController {

    /**
     * Service métier permettant la gestion des pièces.
     */
    private final PieceService pieceService;

    /**
     * Constructeur du controleur de l'api des pièces
     * @param pieceService Service utilisé pour accéder aux données
     *                      des pièces
     */
    public PieceController(PieceService pieceService) {
        this.pieceService = pieceService;
    }

    /**
     * Récupère la liste des pièces
     * @return la liste des pièces ou une liste vide s'il n'y en a pas
     */
    @GetMapping
    public ResponseEntity<List<Piece>> getAllPieces() {
        return ResponseEntity.ok(pieceService.getAllPieces());
    }

    /**
     * Récupère une pièce en fonction de son id
     * @param id l'id du pièce à récupérer
     * @return la pièce et un code
     *              - 200 si la pièce est trouvé
     *              - 400 s'il n'est pas trouvé
     */
    @GetMapping("/{id}")
    public ResponseEntity<Piece> getPieceById(@PathVariable String id) {
        Piece piece = pieceService.getPieceById(id);

        if (piece != null) {
            return ResponseEntity.ok(piece);
        } else {
            return ResponseEntity.notFound().build();
        }
    }

    /**
     * Crée une nouvelle pièce
     * @param piece la pièce à créer
     * @return La pièce créée et un code
     *              - 201 la pièce a bien été créé
     *              - 400 si la requête est erronée
     */
    @PostMapping
    public ResponseEntity<Piece> createPiece(@RequestBody Piece piece) {
        try {
            Piece pieceCree = pieceService.createPiece(piece);

            URI location = ServletUriComponentsBuilder
                    .fromCurrentRequest()
                    .buildAndExpand(pieceCree.getId())
                    .toUri();

            return ResponseEntity.created(location).build();
        } catch (IllegalArgumentException e) {
            return ResponseEntity.badRequest().build();
        }
    }

    /**
     * Modifie une pièce
     * @param id l'id de la randonnée à modifier
     * @param piece la pièce modifié
     * @return La pièce et un code
     *              - 200 si la modification a été effectuée
     *              - 404 si la pièce à modifier n'as pas été trouvé
     *              - 400 si la requête est erronée
     */
    @PutMapping("/{id}")
    public ResponseEntity<Piece> updatePiece(@PathVariable String id, @RequestBody Piece piece) {
        try {
            Piece pieceModifie = pieceService.updatePiece(id, piece);

            if (piece == null) {
                return ResponseEntity.notFound().build();
            }

            return ResponseEntity.ok(pieceModifie);
        } catch (IllegalArgumentException e) {
            return ResponseEntity.badRequest().build();
        }
    }

    /**
     * Supprime une pièce
     * @param id l'id de la pièce à modifier
     * @return Un code
     *              - 204 si la pièce a bien été supprimé
     *              - 404 si la pièce n'as pas été trouvé
     */
    @DeleteMapping("/{id}")
    public ResponseEntity<Piece> deletePiece(@PathVariable String id) {
        if(pieceService.deletePiece(id)) {
            return ResponseEntity.noContent().build();
        } else {
            return ResponseEntity.notFound().build();
        }
    }
}