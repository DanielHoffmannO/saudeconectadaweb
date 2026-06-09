using SaudeConectada.Domain.Enums;

namespace SaudeConectada.Domain.Entities;

public class Notificacao
{
    public int Id { get; set; }
    public int UsuarioId { get; set; }
    public TipoNotificacao Tipo { get; set; }
    public string Titulo { get; set; } = string.Empty;
    public string Mensagem { get; set; } = string.Empty;
    public bool Lida { get; set; }
    public DateTime CriadaEm { get; set; }

    public Usuario Usuario { get; set; } = null!;
}
