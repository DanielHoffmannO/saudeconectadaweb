using SaudeConectada.Domain.Enums;

namespace SaudeConectada.Domain.DTOs.Exames;

public record ExameResponse(int Id, int PacienteId, string PacienteNome, int MedicoId, string MedicoNome, string Tipo, string Laboratorio, DateTime DataSolicitacao, DateTime? DataRealizacao, StatusExame Status, string? Resultado, string? Observacoes);
