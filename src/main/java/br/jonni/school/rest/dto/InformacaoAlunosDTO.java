package br.jonni.school.rest.dto;

import lombok.AllArgsConstructor;
import lombok.Builder;
import lombok.Data;

@Data
@AllArgsConstructor
@Builder
public class InformacaoAlunosDTO {
    private Integer codigo;
    private String nomeAluno;
}