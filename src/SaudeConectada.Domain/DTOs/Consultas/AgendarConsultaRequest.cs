namespace SaudeConectada.Domain.DTOs.Consultas;

public record AgendarConsultaRequest(int MedicoId, int PacienteId, DateTime DataHora, string? Observacoes);
