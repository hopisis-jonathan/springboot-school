package br.jonni.school.domain.repository;

import br.jonni.school.domain.entity.AlunosTurma;
import br.jonni.school.domain.entity.Turma;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.data.jpa.repository.Query;
import org.springframework.data.repository.query.Param;

import java.util.Optional;

public interface AlunosTurmaRepository extends JpaRepository<AlunosTurma, Integer> {
}
