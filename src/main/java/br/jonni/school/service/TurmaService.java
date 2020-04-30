package br.jonni.school.service;

import br.jonni.school.domain.entity.Turma;
import br.jonni.school.domain.entity.enums.StatusTurma;
import br.jonni.school.rest.dto.AlunosTurmaDTO;
import br.jonni.school.rest.dto.TurmaDTO;

import java.util.List;
import java.util.Optional;

public interface TurmaService {
    Turma salvar(TurmaDTO dto);

    void salvarAlunoTurma(AlunosTurmaDTO aluno);

    Optional<Turma> obterTurma(Integer id);

    List<Turma> obterTurmas();

    void atualizaStatus(Integer id, StatusTurma status);
}
