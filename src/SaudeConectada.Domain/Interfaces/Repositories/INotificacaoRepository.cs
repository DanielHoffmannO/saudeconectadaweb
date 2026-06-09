using SaudeConectada.Domain.Entities;

namespace SaudeConectada.Domain.Interfaces.Repositories;

public interface INotificacaoRepository : IRepository<Notificacao>
{
    Task<IEnumerable<Notificacao>> GetByUsuarioIdAsync(int usuarioId);
    Task<IEnumerable<Notificacao>> GetNaoLidasAsync(int usuarioId);
}
