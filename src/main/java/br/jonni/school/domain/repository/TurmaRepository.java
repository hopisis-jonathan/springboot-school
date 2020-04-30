package br.jonni.school.domain.repository;

import br.jonni.school.domain.entity.Professor;
import br.jonni.school.domain.entity.Turma;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.data.jpa.repository.Query;
import org.springframework.data.repository.query.Param;

import java.util.List;
import java.util.Optional;

public interface TurmaRepository extends JpaRepository<Turma, Integer> {

    List<Turma> findByProfessor(Professor professor);

    @Query("SELECT t FROM Turma t LEFT JOIN t.professor WHERE t.id = :id")
    Optional<Turma> findByIdFetchProfessor(@Param("id") Integer id);

    @Query("SELECT t FROM Turma t LEFT JOIN t.professor")
    List<Turma> findAllFetchProfessor();
}
