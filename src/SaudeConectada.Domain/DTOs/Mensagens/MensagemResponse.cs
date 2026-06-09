namespace SaudeConectada.Domain.DTOs.Mensagens;

public record MensagemResponse(int Id, int RemetenteId, string RemetenteNome, int DestinatarioId, string DestinatarioNome, string Conteudo, DateTime EnviadaEm, bool Lida);
