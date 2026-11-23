package fr.tgv.api.service;

import fr.tgv.api.model.Colis;
import fr.tgv.api.repository.ColisRepository;
import org.springframework.stereotype.Service;

import java.util.List;

@Service
public class ColisService {

    private final ColisRepository colisRepository;

    public ColisService(ColisRepository colisRepository) {
        this.colisRepository = colisRepository;
    }

    public List<Colis> getAllColis() {
        return colisRepository.findAll();
    }

    public Colis getColisById(String id) {
        return colisRepository.findById(id).orElse(null);
    }

    public Colis createColis(Colis colis) {
        return colisRepository.save(colis);
    }

    public Colis updateColis(String id, Colis colis) {
        colis.setId(id);
        return colisRepository.save(colis);
    }

    public void deleteColis(String id) {
        colisRepository.deleteById(id);
    }
}