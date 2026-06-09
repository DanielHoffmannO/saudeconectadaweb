namespace SaudeConectada.Domain.Entities;

public class Mensagem
{
    public int Id { get; set; }
    public int RemetenteId { get; set; }
    public int DestinatarioId { get; set; }
    public string Conteudo { get; set; } = string.Empty;
    public DateTime EnviadaEm { get; set; }
    public bool Lida { get; set; }

    public Usuario Remetente { get; set; } = null!;
    public Usuario Destinatario { get; set; } = null!;
}
