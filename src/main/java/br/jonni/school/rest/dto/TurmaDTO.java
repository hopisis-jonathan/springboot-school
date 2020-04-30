package br.jonni.school.rest.dto;

import br.jonni.school.validation.NotEmptyList;
import com.fasterxml.jackson.annotation.JsonIgnore;
import lombok.AllArgsConstructor;
import lombok.Data;
import lombok.NoArgsConstructor;

import javax.validation.constraints.NotNull;
import java.util.List;

@Data
@NoArgsConstructor
@AllArgsConstructor
public class TurmaDTO {
    @JsonIgnore
    private Integer id;

    @NotNull(message = "{campo.codigo-professor.obrigatorio}")
    private Integer professor;

    @NotNull(message = "{campo.nome-turma.obrigatorio}")
    private String nomeTurma;

    @NotNull(message = "{campo.max-alunos.obrigatorio}")
    private Integer maximoAlunos;

    @NotEmptyList(message = "{campo.alunos-turma.obrigatorio}")
    private List<AlunosTurmaDTO> alunos;

}

