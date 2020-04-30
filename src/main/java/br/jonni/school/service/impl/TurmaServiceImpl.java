package br.jonni.school.service.impl;

import br.jonni.school.domain.entity.Aluno;
import br.jonni.school.domain.entity.AlunosTurma;
import br.jonni.school.domain.entity.Professor;
import br.jonni.school.domain.entity.Turma;
import br.jonni.school.domain.entity.enums.StatusTurma;
import br.jonni.school.domain.repository.AlunoRepository;
import br.jonni.school.domain.repository.AlunosTurmaRepository;
import br.jonni.school.domain.repository.ProfessorRepository;
import br.jonni.school.domain.repository.TurmaRepository;
import br.jonni.school.exception.MaximoExcedidoException;
import br.jonni.school.exception.RegraNegocioException;
import br.jonni.school.exception.TurmaNaoEncontradoException;
import br.jonni.school.rest.dto.AlunosTurmaDTO;
import br.jonni.school.rest.dto.TurmaDTO;
import br.jonni.school.service.TurmaService;

import lombok.RequiredArgsConstructor;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import javax.validation.constraints.Null;
import java.util.List;
import java.util.Optional;
import java.util.stream.Collectors;

@Service
@RequiredArgsConstructor
public class TurmaServiceImpl implements TurmaService {

    private final TurmaRepository repository;
    private final ProfessorRepository professorRepository;
    private final AlunoRepository alunoRepository;
    private final AlunosTurmaRepository alunosTurmaRepository;

    @Override
    @Transactional
    public Turma salvar(TurmaDTO dto ) {
        Integer idProfessor = dto.getProfessor();
        Professor professor = professorRepository
                .findById(idProfessor)
                .orElseThrow(() -> new RegraNegocioException("Código de professor inválido."));

        Turma turma = new Turma();
        turma.setId(dto.getId());
        turma.setNomeTurma(dto.getNomeTurma());
        turma.setMaximoAlunos(dto.getMaximoAlunos());
        turma.setProfessor(professor);
        turma.setStatus(StatusTurma.ABERTA);

        List<AlunosTurma> alunosTurma = converterItems(turma, dto.getAlunos());
        Integer tamTA = dto.getAlunos().size();
        if (turma.getAlunos() != null){
            tamTA = turma.getAlunos().size();
        }
        if(turma.getMaximoAlunos() < tamTA){
            throw new MaximoExcedidoException("O limite de alunos na turma foi excedido do máximo "+turma.getMaximoAlunos());
        }
        repository.save(turma);
        alunosTurmaRepository.saveAll(alunosTurma);
        turma.setAlunos(alunosTurma);
        return turma;
    }

    @Override
    public void salvarAlunoTurma(AlunosTurmaDTO dto) {

        Turma turma = repository
                .findById(dto.getTurma())
                .orElseThrow(
                        () -> new RegraNegocioException(
                                "Código de turma inválida: "+ dto.getTurma()
                        ));

        Aluno aluno = alunoRepository
                .findById(dto.getAluno())
                .orElseThrow(
                        () -> new RegraNegocioException(
                                "Código de aluno inválido: "+ dto.getAluno()
                        ));
        AlunosTurma novo = new AlunosTurma();
        novo.setId(dto.getId());
        novo.setAluno(aluno);
        novo.setTurma(turma);
        turma.getAlunos().add(novo);
        if(turma.getMaximoAlunos() < turma.getAlunos().size()){
            System.out.println("Atingiu o máximo");
            throw new MaximoExcedidoException("O limite de alunos na turma foi excedido do máximo "+turma.getMaximoAlunos());
        }

        alunosTurmaRepository.save(novo);
    }

    @Override
    public Optional<Turma> obterTurma(Integer id) {
        return repository.findByIdFetchProfessor(id);
    }


    @Override
    public List<Turma> obterTurmas() {
        return repository.findAllFetchProfessor();
    }


    @Override
    @Transactional
    public void atualizaStatus( Integer id, StatusTurma statusTurma ) {
        repository
                .findById(id)
                .map( turma -> {
                    turma.setStatus(statusTurma);
                    return repository.save(turma);
                }).orElseThrow(() -> new TurmaNaoEncontradoException("A turma não foi encontrada") );
    }


    private List<AlunosTurma> converterItems(Turma turma, List<AlunosTurmaDTO> items){
        if(items.isEmpty()){
            throw new RegraNegocioException("Não é possível realizar um turma sem items.");
        }

        return items
                .stream()
                .map( dto -> {
                    Integer idAluno = dto.getAluno();
                    Aluno aluno = alunoRepository
                            .findById(idAluno)
                            .orElseThrow(
                                    () -> new RegraNegocioException(
                                            "Código de aluno inválido: "+ idAluno
                                    ));

                    AlunosTurma alunosTurma = new AlunosTurma();
                    alunosTurma.setId(dto.getId());
                    alunosTurma.setTurma(turma);
                    alunosTurma.setAluno(aluno);
                    return alunosTurma;
                }).collect(Collectors.toList());

    }


}