using SaudeConectada.Domain.Enums;

namespace SaudeConectada.Domain.Entities;

public class Exame
{
    public int Id { get; set; }
    public int PacienteId { get; set; }
    public int MedicoId { get; set; }
    public string Tipo { get; set; } = string.Empty;
    public string Laboratorio { get; set; } = string.Empty;
    public DateTime DataSolicitacao { get; set; }
    public DateTime? DataRealizacao { get; set; }
    public StatusExame Status { get; set; }
    public string? Resultado { get; set; }
    public string? Observacoes { get; set; }

    public Paciente Paciente { get; set; } = null!;
    public Medico Medico { get; set; } = null!;
}
