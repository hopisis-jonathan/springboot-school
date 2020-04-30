package br.jonni.school.rest.controller;

import br.jonni.school.domain.entity.Aluno;
import br.jonni.school.domain.repository.AlunoRepository;
import org.springframework.data.domain.Example;
import org.springframework.data.domain.ExampleMatcher;
import org.springframework.http.HttpStatus;
import org.springframework.web.bind.annotation.*;
import org.springframework.web.server.ResponseStatusException;

import javax.validation.Valid;
import java.util.List;

@RestController
@RequestMapping("/api/alunos")
public class AlunoController {
    private AlunoRepository alunos;

    public AlunoController(AlunoRepository alunos) {
        this.alunos = alunos;
    }


    @GetMapping( value = "{id}")
    public Aluno getAlunoById(@PathVariable Integer id){

        return alunos
                .findById(id)
                .orElseThrow( () ->
                        new ResponseStatusException(HttpStatus.NOT_FOUND, "Aluno não encontrado"));

    }

    @PostMapping
    @ResponseStatus(HttpStatus.CREATED)
    public Aluno save( @RequestBody @Valid Aluno aluno) {
        return alunos.save(aluno);
    }

    @DeleteMapping(value = "{id}")
    @ResponseStatus(HttpStatus.NO_CONTENT)
    public void delete(@PathVariable Integer id){

        alunos
                .findById(id)
                .map( aluno -> {
                    alunos.delete(aluno);
                    return true;
                })
                .orElseThrow( () ->
                        new ResponseStatusException(HttpStatus.NOT_FOUND, "Aluno não encontrado"));
    }

    @PutMapping(value = "{id}")
    @ResponseStatus(HttpStatus.NO_CONTENT)
    public void update(@PathVariable Integer id,
                       @RequestBody @Valid Aluno aluno){
        alunos
                .findById(id)
                .map(alunoExistente -> {
                    aluno.setId(alunoExistente.getId());
                    alunos.save(aluno);
                    return alunoExistente;
                }).orElseThrow( () ->
                new ResponseStatusException(HttpStatus.NOT_FOUND, "Aluno não encontrado"));
    }

    @GetMapping
    public List<AlunoRepository> find(Aluno filtro){
        ExampleMatcher matcher = ExampleMatcher
                .matching()
                .withIgnoreCase()
                .withStringMatcher( ExampleMatcher.StringMatcher.CONTAINING);
        Example example = Example.of(filtro, matcher);

        return alunos.findAll(example);

    }

}
