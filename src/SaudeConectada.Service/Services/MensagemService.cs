using SaudeConectada.Domain.Entities;
using SaudeConectada.Domain.Interfaces.Repositories;
using SaudeConectada.Domain.Interfaces.Services;

namespace SaudeConectada.Service.Services;

public class MensagemService : IMensagemService
{
    private readonly IMensagemRepository _mensagemRepository;

    public MensagemService(IMensagemRepository mensagemRepository)
    {
        _mensagemRepository = mensagemRepository;
    }

    public async Task<Mensagem> EnviarAsync(Mensagem mensagem)
    {
        mensagem.EnviadaEm = DateTime.UtcNow;
        mensagem.Lida = false;
        await _mensagemRepository.AddAsync(mensagem);
        await _mensagemRepository.SaveChangesAsync();
        return mensagem;
    }

    public async Task<IEnumerable<Mensagem>> GetConversaAsync(int usuarioId, int outroUsuarioId)
        => await _mensagemRepository.GetConversaAsync(usuarioId, outroUsuarioId);

    public async Task<IEnumerable<Mensagem>> GetRecebidosAsync(int destinatarioId)
        => await _mensagemRepository.GetByDestinatarioIdAsync(destinatarioId);
}
