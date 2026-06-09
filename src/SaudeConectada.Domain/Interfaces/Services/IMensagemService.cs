using SaudeConectada.Domain.Entities;

namespace SaudeConectada.Domain.Interfaces.Services;

public interface IMensagemService
{
    Task<Mensagem> EnviarAsync(Mensagem mensagem);
    Task<IEnumerable<Mensagem>> GetConversaAsync(int usuarioId, int outroUsuarioId);
    Task<IEnumerable<Mensagem>> GetRecebidosAsync(int destinatarioId);
}
