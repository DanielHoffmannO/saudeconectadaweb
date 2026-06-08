using SaudeConectada.Domain.Enums;

namespace SaudeConectada.Domain.DTOs.Medicos;

public record CreateMedicoRequest(string Nome, string CRM, Especialidade Especialidade, string Email, string Telefone);
