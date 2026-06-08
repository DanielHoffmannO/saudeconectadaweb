using SaudeConectada.Domain.Enums;

namespace SaudeConectada.Domain.DTOs.Consultas;

public record ConsultaResponse(int Id, int MedicoId, string MedicoNome, int PacienteId, string PacienteNome, DateTime DataHora, StatusConsulta Status, string? Observacoes);
