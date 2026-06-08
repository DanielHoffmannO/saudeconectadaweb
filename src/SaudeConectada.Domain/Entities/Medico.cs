using SaudeConectada.Domain.Enums;

namespace SaudeConectada.Domain.Entities;

public class Medico
{
    public int Id { get; set; }
    public string Nome { get; set; } = string.Empty;
    public string CRM { get; set; } = string.Empty;
    public Especialidade Especialidade { get; set; }
    public string Email { get; set; } = string.Empty;
    public string Telefone { get; set; } = string.Empty;
}
