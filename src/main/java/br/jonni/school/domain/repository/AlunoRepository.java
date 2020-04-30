package br.jonni.school.domain.repository;

import br.jonni.school.domain.entity.Aluno;
import org.springframework.data.jpa.repository.JpaRepository;

public interface AlunoRepository  extends JpaRepository<Aluno, Integer> {
}
