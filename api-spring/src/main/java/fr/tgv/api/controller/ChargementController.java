package fr.tgv.api.controller;

import fr.tgv.api.model.Tournee;
import fr.tgv.api.service.ChargementService;
import org.springframework.web.bind.annotation.*;

@RestController
public class ChargementController {

    private final ChargementService chargementService;

    public ChargementController(ChargementService chargementService) {
        this.chargementService = chargementService;
    }

    @PostMapping("/tournees/{tourneeId}/optimiser-chargement")
    public Tournee optimiserChargement(@PathVariable String tourneeId) {
        return chargementService.optimiserChargement(tourneeId);
    }
}