package fr.tgv.api.controller;

import fr.tgv.api.model.Tournee;
import fr.tgv.api.service.TourneeService;
import org.springframework.web.bind.annotation.*;

import java.util.List;

@RestController
@RequestMapping("/tournees")
public class TourneeController {

    private final TourneeService tourneeService;

    public TourneeController(TourneeService tourneeService) {
        this.tourneeService = tourneeService;
    }

    @GetMapping
    public List<Tournee> getAllTournees() {
        return tourneeService.getAllTournees();
    }

    @GetMapping("/{id}")
    public Tournee getTourneeById(@PathVariable String id) {
        return tourneeService.getTourneeById(id);
    }

    @PostMapping
    public Tournee createTournee(@RequestBody Tournee tournee) {
        return tourneeService.createTournee(tournee);
    }

    @PutMapping("/{id}")
    public Tournee updateTournee(@PathVariable String id, @RequestBody Tournee tournee) {
        return tourneeService.updateTournee(id, tournee);
    }

    @DeleteMapping("/{id}")
    public void deleteTournee(@PathVariable String id) {
        tourneeService.deleteTournee(id);
    }
}