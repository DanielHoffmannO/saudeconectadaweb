namespace SaudeConectada.Domain.DTOs.Pacientes;

public record PacienteResponse(int Id, string Nome, string CPF, string Email, string Telefone, DateTime DataNascimento);
