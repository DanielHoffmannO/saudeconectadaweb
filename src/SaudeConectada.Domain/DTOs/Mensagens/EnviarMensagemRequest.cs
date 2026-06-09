namespace SaudeConectada.Domain.DTOs.Mensagens;

public record EnviarMensagemRequest(int DestinatarioId, string Conteudo);
