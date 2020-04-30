package br.jonni.school.domain.entity;

import lombok.AllArgsConstructor;
import lombok.Data;
import lombok.NoArgsConstructor;

import javax.persistence.*;
import javax.validation.constraints.NotNull;

@Entity
@NoArgsConstructor
@AllArgsConstructor
@Data
@Table(name = "alunos_turma")
public class AlunosTurma {
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Column(name = "id")
    private Integer id;

    @ManyToOne
    @JoinColumn(name="turma_id")
    @NotNull(message = "{campo.turma.obrigatorio}")
    private Turma turma;

    @ManyToOne
    @JoinColumn(name="aluno_id")
    @NotNull(message = "{campo.aluno.obrigatorio}")
    private Aluno aluno;

}
