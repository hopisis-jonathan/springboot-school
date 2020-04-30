package br.jonni.school.domain.entity;

import br.jonni.school.domain.entity.enums.StatusTurma;
import lombok.AllArgsConstructor;
import lombok.Data;
import lombok.NoArgsConstructor;

import javax.persistence.*;
import java.math.BigDecimal;
import java.time.LocalDate;
import java.util.List;

@Entity
@NoArgsConstructor
@AllArgsConstructor
@Data
@Table( name="turma" )
public class Turma {
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Integer id;

    @ManyToOne
    @JoinColumn(name = "professor")
    private Professor professor;

    @Column(name = "nome_turma")
    private String nomeTurma;

    @Column(name = "max_aluno")
    private Integer maximoAlunos;

    @OneToMany(mappedBy = "turma")
    private List<AlunosTurma> alunos;

    @Enumerated(EnumType.STRING)
    @Column(name = "status")
    private StatusTurma status;

}
