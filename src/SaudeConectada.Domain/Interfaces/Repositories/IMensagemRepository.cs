using SaudeConectada.Domain.Entities;

namespace SaudeConectada.Domain.Interfaces.Repositories;

public interface IMensagemRepository : IRepository<Mensagem>
{
    Task<IEnumerable<Mensagem>> GetConversaAsync(int usuarioId, int outroUsuarioId);
    Task<IEnumerable<Mensagem>> GetByDestinatarioIdAsync(int destinatarioId);
}
