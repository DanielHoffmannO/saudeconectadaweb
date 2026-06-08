using SaudeConectada.Domain.Enums;

namespace SaudeConectada.Domain.Entities;

public class Consulta
{
    public int Id { get; set; }
    public int MedicoId { get; set; }
    public int PacienteId { get; set; }
    public DateTime DataHora { get; set; }
    public StatusConsulta Status { get; set; }
    public string? Observacoes { get; set; }

    public Medico Medico { get; set; } = null!;
    public Paciente Paciente { get; set; } = null!;
}
