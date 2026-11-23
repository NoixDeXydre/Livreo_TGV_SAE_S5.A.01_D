package fr.tgv.api.controller;

import fr.tgv.api.model.Colis;
import fr.tgv.api.service.ColisService;
import org.springframework.web.bind.annotation.*;

import java.util.List;

@RestController
@RequestMapping("/colis")
public class ColisController {

    private final ColisService colisService;

    public ColisController(ColisService colisService) {
        this.colisService = colisService;
    }

    @GetMapping
    public List<Colis> getAllColis() {
        return colisService.getAllColis();
    }

    @GetMapping("/{id}")
    public Colis getColisById(@PathVariable String id) {
        return colisService.getColisById(id);
    }

    @PostMapping
    public Colis createColis(@RequestBody Colis colis) {
        return colisService.createColis(colis);
    }

    @PutMapping("/{id}")
    public Colis updateColis(@PathVariable String id, @RequestBody Colis colis) {
        return colisService.updateColis(id, colis);
    }

    @DeleteMapping("/{id}")
    public void deleteColis(@PathVariable String id) {
        colisService.deleteColis(id);
    }
}