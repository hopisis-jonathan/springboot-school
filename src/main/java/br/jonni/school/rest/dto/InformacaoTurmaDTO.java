package br.jonni.school.rest.dto;

import com.fasterxml.jackson.annotation.JsonIgnore;
import lombok.AllArgsConstructor;
import lombok.Builder;
import lombok.Data;

import java.math.BigDecimal;
import java.util.List;

@Data
@AllArgsConstructor
@Builder
public class InformacaoTurmaDTO {
    private Integer codigo;
    private Integer codigoProfessor;
    private String nomeProfessor;
    private String nomeTurma;
    private Integer maximoAlunos;
    private String status;
    private List<InformacaoAlunosDTO> alunos;
    private Integer totalAlunos;
}