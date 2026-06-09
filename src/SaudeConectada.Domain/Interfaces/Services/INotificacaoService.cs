using SaudeConectada.Domain.Entities;

namespace SaudeConectada.Domain.Interfaces.Services;

public interface INotificacaoService
{
    Task<IEnumerable<Notificacao>> GetByUsuarioAsync(int usuarioId);
    Task<IEnumerable<Notificacao>> GetNaoLidasAsync(int usuarioId);
    Task MarcarComoLidaAsync(int id);
}
