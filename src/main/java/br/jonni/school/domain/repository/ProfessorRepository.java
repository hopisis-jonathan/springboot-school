package br.jonni.school.domain.repository;

import br.jonni.school.domain.entity.Professor;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.data.jpa.repository.Query;
import org.springframework.data.repository.query.Param;

import java.util.List;


public interface ProfessorRepository extends JpaRepository<Professor, Integer> {

    List<Professor> findByNomeLike(String nome);

    @Query(value = "SELECT p FROM Professor p WHERE nome LIKE :nome")
    List<Professor> encontrarPorNome(@Param("nome") String nome);

    boolean existsByNome(String nome);

    @Query(" select p from Professor p left join fetch p.turmas where p.id = :id  ")
    Professor findProfessorFetchTurmas( @Param("id") Integer id );


}
