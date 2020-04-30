package br.jonni.school.rest.dto;

import com.fasterxml.jackson.annotation.JsonIgnore;
import lombok.AllArgsConstructor;
import lombok.Data;
import lombok.NoArgsConstructor;

@Data
@NoArgsConstructor
@AllArgsConstructor
public class AlunosTurmaDTO {
    @JsonIgnore
    private Integer id;

    private Integer aluno;
    private Integer turma;
}
