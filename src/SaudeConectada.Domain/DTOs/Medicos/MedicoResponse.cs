using SaudeConectada.Domain.Enums;

namespace SaudeConectada.Domain.DTOs.Medicos;

public record MedicoResponse(int Id, string Nome, string CRM, Especialidade Especialidade, string Email, string Telefone);
