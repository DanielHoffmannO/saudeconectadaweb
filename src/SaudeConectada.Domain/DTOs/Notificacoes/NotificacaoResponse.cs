using SaudeConectada.Domain.Enums;

namespace SaudeConectada.Domain.DTOs.Notificacoes;

public record NotificacaoResponse(int Id, TipoNotificacao Tipo, string Titulo, string Mensagem, bool Lida, DateTime CriadaEm);
