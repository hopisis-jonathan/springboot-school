package br.jonni.school.exception;

public class SenhaInvalidaException extends RuntimeException  {
    public SenhaInvalidaException() {
        super("Senha inválida");
    }
}
