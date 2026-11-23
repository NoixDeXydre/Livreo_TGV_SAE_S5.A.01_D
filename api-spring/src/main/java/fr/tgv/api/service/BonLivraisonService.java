package fr.tgv.api.service;

import fr.tgv.api.model.BonLivraison;
import fr.tgv.api.repository.BonLivraisonRepository;
import org.springframework.stereotype.Service;

import java.util.List;

@Service
public class BonLivraisonService {

    private final BonLivraisonRepository bonLivraisonRepository;

    public BonLivraisonService(BonLivraisonRepository bonLivraisonRepository) {
        this.bonLivraisonRepository = bonLivraisonRepository;
    }

    public List<BonLivraison> getAllBonsLivraison() {
        return bonLivraisonRepository.findAll();
    }

    public BonLivraison getBonLivraisonById(String id) {
        return bonLivraisonRepository.findById(id).orElse(null);
    }

    public BonLivraison createBonLivraison(BonLivraison bonLivraison) {
        return bonLivraisonRepository.save(bonLivraison);
    }

    public BonLivraison updateBonLivraison(String id, BonLivraison bonLivraison) {
        bonLivraison.setId(id);
        return bonLivraisonRepository.save(bonLivraison);
    }

    public void deleteBonLivraison(String id) {
        bonLivraisonRepository.deleteById(id);
    }
}