package fr.tgv.api.service;

import fr.tgv.api.model.Tournee;
import fr.tgv.api.repository.TourneeRepository;
import org.springframework.stereotype.Service;

import java.util.List;

@Service
public class TourneeService {

    private final TourneeRepository tourneeRepository;

    public TourneeService(TourneeRepository tourneeRepository) {
        this.tourneeRepository = tourneeRepository;
    }

    public List<Tournee> getAllTournees() {
        return tourneeRepository.findAll();
    }

    public Tournee getTourneeById(String id) {
        return tourneeRepository.findById(id).orElse(null);
    }

    public Tournee createTournee(Tournee tournee) {
        return tourneeRepository.save(tournee);
    }

    public Tournee updateTournee(String id, Tournee tournee) {
        tournee.setId(id);
        return tourneeRepository.save(tournee);
    }

    public void deleteTournee(String id) {
        tourneeRepository.deleteById(id);
    }
}