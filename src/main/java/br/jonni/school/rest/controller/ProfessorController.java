package br.jonni.school.rest.controller;

import br.jonni.school.domain.entity.Professor;
import br.jonni.school.domain.repository.ProfessorRepository;
import org.springframework.data.domain.Example;
import org.springframework.data.domain.ExampleMatcher;
import org.springframework.http.HttpStatus;
import org.springframework.web.bind.annotation.*;
import org.springframework.web.server.ResponseStatusException;

import javax.validation.Valid;
import java.util.List;

@RestController
@RequestMapping("/api/professor")
public class ProfessorController {

    private ProfessorRepository repository;

    public ProfessorController(ProfessorRepository professor) {
        this.repository = professor;
    }


    @GetMapping( value = "{id}")
    public Professor getProfessorById(@PathVariable Integer id){

        return repository
                .findById(id)
                .orElseThrow( () ->
                        new ResponseStatusException(HttpStatus.NOT_FOUND, "Professor não encontrado"));

    }

    @PostMapping
    @ResponseStatus(HttpStatus.CREATED)
    public Professor save( @RequestBody @Valid Professor professor) {
        return repository.save(professor);
    }

    @DeleteMapping(value = "{id}")
    @ResponseStatus(HttpStatus.NO_CONTENT)
    public void delete(@PathVariable Integer id){

        repository
                .findById(id)
                .map( cliente -> {
                    repository.delete(cliente);
                    return true;
                })
                .orElseThrow( () ->
                        new ResponseStatusException(HttpStatus.NOT_FOUND, "Professor não encontrado"));
    }

    @PutMapping(value = "{id}")
    @ResponseStatus(HttpStatus.NO_CONTENT)
    public void update(@PathVariable Integer id,
                       @RequestBody  @Valid Professor professor){
        repository
                .findById(id)
                .map(clienteExistente -> {
                    professor.setId(clienteExistente.getId());
                    repository.save(professor);
                    return clienteExistente;
                }).orElseThrow( () ->
                new ResponseStatusException(HttpStatus.NOT_FOUND, "Cliente não encontrado"));
    }

    @GetMapping
    public List<ProfessorRepository> find(Professor filtro){
        ExampleMatcher matcher = ExampleMatcher
                .matching()
                .withIgnoreCase()
                .withStringMatcher( ExampleMatcher.StringMatcher.CONTAINING);
        Example example = Example.of(filtro, matcher);

        return repository.findAll(example);

    }
}
