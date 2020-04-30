package br.jonni.school.rest.controller;

import br.jonni.school.domain.entity.AlunosTurma;
import br.jonni.school.domain.entity.Professor;
import br.jonni.school.domain.entity.Turma;
import br.jonni.school.domain.entity.enums.StatusTurma;
import br.jonni.school.domain.repository.ProfessorRepository;
import br.jonni.school.domain.repository.TurmaRepository;
import br.jonni.school.exception.MaximoExcedidoException;
import br.jonni.school.rest.dto.*;
import br.jonni.school.service.TurmaService;
import org.springframework.data.domain.Example;
import org.springframework.data.domain.ExampleMatcher;
import org.springframework.http.HttpStatus;
import org.springframework.util.CollectionUtils;
import org.springframework.web.bind.annotation.*;
import org.springframework.web.server.ResponseStatusException;

import javax.validation.Valid;
import java.lang.reflect.Array;
import java.util.ArrayList;
import java.util.Collections;
import java.util.List;
import java.util.stream.Collectors;

import static org.springframework.http.HttpStatus.NO_CONTENT;

@RestController
@RequestMapping("/api/turmas")
public class TurmaController {

    private TurmaService service;
    private TurmaRepository repository;

    public TurmaController(TurmaService service, TurmaRepository repository) {
        this.repository = repository;
        this.service = service;
    }

    @PostMapping
    @ResponseStatus(HttpStatus.CREATED)
    public Integer save(@RequestBody @Valid TurmaDTO dto) {
        Turma turma = service.salvar(dto);
        return turma.getId();

    }

    @PutMapping("{id}")
    @ResponseStatus(HttpStatus.CREATED)
    public Integer update(@PathVariable Integer id, @RequestBody @Valid TurmaDTO dto) {
        dto.setId(id);
        Turma turma = service.salvar(dto);
        return turma.getId();

    }

    @GetMapping("{id}")
    public InformacaoTurmaDTO getById(@PathVariable Integer id) {
        return service
                .obterTurma(id)
                .map(p -> converter(p))
                .orElseThrow(() -> new ResponseStatusException(HttpStatus.NOT_FOUND, "Turma não econtrado"));
    }

    @GetMapping
    public @ResponseBody List<InformacaoTurmaDTO> listarTodos(){
        return service.obterTurmas()
                .stream()
                .map(item -> InformacaoTurmaDTO
                        .builder()
                        .codigo(item.getId())
                        .nomeTurma(item.getNomeTurma())
                        .nomeProfessor(item.getProfessor().getNome())
                        .maximoAlunos(item.getMaximoAlunos())
                        .totalAlunos(item.getAlunos().size())
                        .status(item.getStatus().name())
                        .codigoProfessor(item.getProfessor().getId())
                        .alunos(new ArrayList())
                        .build()
        ).collect(Collectors.toList());

    }

    private InformacaoTurmaDTO converter(Turma turma) {
        return InformacaoTurmaDTO
                .builder()
                .codigo(turma.getId())
                .nomeProfessor(turma.getProfessor().getNome())
                .maximoAlunos(turma.getMaximoAlunos())
                .nomeTurma(turma.getNomeTurma())
                .status(turma.getStatus().name())
                .alunos(converter(turma.getAlunos()))
                .build();
    }

    @PostMapping
    @RequestMapping("/alunos")
    @ResponseStatus(NO_CONTENT)
    public void save(@RequestBody @Valid AlunosTurmaDTO aluno) {
         service.salvarAlunoTurma(aluno);
    }


    private List<InformacaoAlunosDTO> converter(List<AlunosTurma> itens) {
        if (CollectionUtils.isEmpty(itens)) {
            return Collections.emptyList();
        }

        return itens.stream().map(
                item -> InformacaoAlunosDTO
                        .builder()
                        .codigo(item.getId())
                        .nomeAluno(item.getAluno().getNome())
                        .build()
        ).collect(Collectors.toList());
    }

    @PatchMapping("{id}")
    @ResponseStatus(NO_CONTENT)
    public void updateStatus(@PathVariable Integer id,
                             @RequestBody AtualizacaoStatusTurmaDTO dto) {
        String novoStatus = dto.getNovoStatus();
        service.atualizaStatus(id, StatusTurma.valueOf(novoStatus));
    }
}
