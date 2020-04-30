package br.jonni.school.rest.controller;

import br.jonni.school.domain.repository.AlunosTurmaRepository;
import br.jonni.school.exception.MaximoExcedidoException;
import br.jonni.school.rest.dto.AlunosTurmaDTO;
import br.jonni.school.rest.dto.InformacaoTurmaDTO;
import br.jonni.school.service.TurmaService;
import org.springframework.http.HttpStatus;
import org.springframework.web.bind.annotation.*;
import org.springframework.web.server.ResponseStatusException;

import javax.validation.Valid;

@RestController
@RequestMapping("/api/turma/aluno")
public class AlunosTurmaController {

    private AlunosTurmaRepository repository;

    private TurmaService turma;


    public AlunosTurmaController(AlunosTurmaRepository aluno, TurmaService turma) {
        this.repository = aluno;
        this.turma = turma;
    }

    @PostMapping
    @ResponseStatus(HttpStatus.CREATED)
    public AlunosTurmaDTO save(@RequestBody @Valid AlunosTurmaDTO aluno) {

        InformacaoTurmaDTO result = turma.obterTurma(aluno.getTurma())
                .map(p ->
                        {
                            return InformacaoTurmaDTO
                                    .builder()
                                    .codigo(p.getId())
                                    .nomeProfessor(p.getProfessor().getNome())
                                    .maximoAlunos(p.getMaximoAlunos())
                                    .status(p.getStatus().name())
                                    .build();
                        }
                ).orElseThrow(() -> new ResponseStatusException(HttpStatus.NOT_FOUND, "Turma não econtrado"));

        Integer total;
        total = result.getAlunos().size();
        if(result.getMaximoAlunos() < total ){
            throw new MaximoExcedidoException("O limite de alunos na turma foi excedido do máximo "+result.getMaximoAlunos());
        }

        turma.salvarAlunoTurma(aluno);

        return aluno;
    }

    @DeleteMapping(value = "{id}")
    @ResponseStatus(HttpStatus.NO_CONTENT)
    public void delete(@PathVariable Integer id) {

        repository
                .findById(id)
                .map(aluno -> {
                    repository.delete(aluno);
                    return true;
                })
                .orElseThrow(() ->
                        new ResponseStatusException(HttpStatus.NOT_FOUND, "Aluno não encontrado"));
    }

}