namespace SaudeConectada.Domain.DTOs.Exames;

public record CreateExameRequest(int PacienteId, int MedicoId, string Tipo, string Laboratorio, string? Observacoes);
