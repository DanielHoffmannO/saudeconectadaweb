namespace SaudeConectada.Domain.DTOs.Pacientes;

public record CreatePacienteRequest(string Nome, string CPF, string Email, string Telefone, DateTime DataNascimento);
