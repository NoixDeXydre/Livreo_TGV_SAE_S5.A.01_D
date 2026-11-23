package fr.tgv.api.service;

import fr.tgv.api.model.Colis;
import fr.tgv.api.model.Piece;
import fr.tgv.api.model.Tournee;
import fr.tgv.api.repository.ColisRepository;
import fr.tgv.api.repository.PieceRepository;
import fr.tgv.api.repository.TourneeRepository;
import org.springframework.http.HttpStatus;
import org.springframework.stereotype.Service;
import org.springframework.web.server.ResponseStatusException;

import java.util.ArrayList;
import java.util.Comparator;
import java.util.List;

@Service
public class ChargementService {

    private final TourneeRepository tourneeRepository;
    private final ColisRepository colisRepository;
    private final PieceRepository pieceRepository;

    public ChargementService(
            TourneeRepository tourneeRepository,
            ColisRepository colisRepository,
            PieceRepository pieceRepository) {
        this.tourneeRepository = tourneeRepository;
        this.colisRepository = colisRepository;
        this.pieceRepository = pieceRepository;
    }

    public Tournee optimiserChargement(String tourneeId) {

        Tournee tournee = tourneeRepository.findById(tourneeId)
                .orElseThrow(() -> new ResponseStatusException(
                        HttpStatus.NOT_FOUND, "Tournée introuvable"));

        if (tournee.getColisIds() == null
                || tournee.getColisIds().isEmpty()) {
            return tournee;
        }

        List<String> colisIds = new ArrayList<>(tournee.getColisIds());

        colisIds.sort(
                Comparator.comparingDouble(this::calculerPoidsColis)
                        .reversed()
        );

        tournee.setColisIds(colisIds);

        return tourneeRepository.save(tournee);
    }

    private double calculerPoidsColis(String colisId) {

        Colis colis = colisRepository.findById(colisId)
                .orElseThrow(() -> new ResponseStatusException(
                        HttpStatus.NOT_FOUND, "Colis introuvable"));

        double poidsTotal = 0;

        if (colis.getPiecesIds() == null) {
            return poidsTotal;
        }

        for (String pieceId : colis.getPiecesIds()) {
            Piece piece = pieceRepository.findById(pieceId)
                    .orElseThrow(() -> new ResponseStatusException(
                            HttpStatus.NOT_FOUND, "Pièce introuvable"));

            poidsTotal += piece.getPoids();
        }

        return poidsTotal;
    }
}