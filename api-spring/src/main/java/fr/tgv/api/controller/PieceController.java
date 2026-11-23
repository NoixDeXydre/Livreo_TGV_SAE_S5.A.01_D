package fr.tgv.api.controller;

import fr.tgv.api.model.Piece;
import fr.tgv.api.service.PieceService;
import org.springframework.web.bind.annotation.*;

import java.util.List;

@RestController
@RequestMapping("/pieces")
public class PieceController {

    private final PieceService pieceService;

    public PieceController(PieceService pieceService) {
        this.pieceService = pieceService;
    }

    @GetMapping
    public List<Piece> getAllPieces() {
        return pieceService.getAllPieces();
    }

    @GetMapping("/{id}")
    public Piece getPieceById(@PathVariable String id) {
        return pieceService.getPieceById(id);
    }

    @PostMapping
    public Piece createPiece(@RequestBody Piece piece) {
        return pieceService.createPiece(piece);
    }

    @PutMapping("/{id}")
    public Piece updatePiece(@PathVariable String id, @RequestBody Piece piece) {
        return pieceService.updatePiece(id, piece);
    }

    @DeleteMapping("/{id}")
    public void deletePiece(@PathVariable String id) {
        pieceService.deletePiece(id);
    }
}