package fr.tgv.api.controller;

import fr.tgv.api.model.BonLivraison;
import fr.tgv.api.service.BonLivraisonService;
import org.springframework.web.bind.annotation.*;

import java.util.List;

@RestController
@RequestMapping("/bons-livraison")
public class BonLivraisonController {

    private final BonLivraisonService bonLivraisonService;

    public BonLivraisonController(BonLivraisonService bonLivraisonService) {
        this.bonLivraisonService = bonLivraisonService;
    }

    @GetMapping
    public List<BonLivraison> getAllBonsLivraison() {
        return bonLivraisonService.getAllBonsLivraison();
    }

    @GetMapping("/{id}")
    public BonLivraison getBonLivraisonById(@PathVariable String id) {
        return bonLivraisonService.getBonLivraisonById(id);
    }

    @PostMapping
    public BonLivraison createBonLivraison(@RequestBody BonLivraison bonLivraison) {
        return bonLivraisonService.createBonLivraison(bonLivraison);
    }

    @PutMapping("/{id}")
    public BonLivraison updateBonLivraison(
            @PathVariable String id,
            @RequestBody BonLivraison bonLivraison) {
        return bonLivraisonService.updateBonLivraison(id, bonLivraison);
    }

    @DeleteMapping("/{id}")
    public void deleteBonLivraison(@PathVariable String id) {
        bonLivraisonService.deleteBonLivraison(id);
    }
}