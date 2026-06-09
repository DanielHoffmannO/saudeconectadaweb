using SaudeConectada.Domain.Entities;
using SaudeConectada.Domain.Interfaces.Repositories;
using SaudeConectada.Domain.Interfaces.Services;

namespace SaudeConectada.Service.Services;

public class NotificacaoService : INotificacaoService
{
    private readonly INotificacaoRepository _notificacaoRepository;

    public NotificacaoService(INotificacaoRepository notificacaoRepository)
    {
        _notificacaoRepository = notificacaoRepository;
    }

    public async Task<IEnumerable<Notificacao>> GetByUsuarioAsync(int usuarioId)
        => await _notificacaoRepository.GetByUsuarioIdAsync(usuarioId);

    public async Task<IEnumerable<Notificacao>> GetNaoLidasAsync(int usuarioId)
        => await _notificacaoRepository.GetNaoLidasAsync(usuarioId);

    public async Task MarcarComoLidaAsync(int id)
    {
        var notificacao = await _notificacaoRepository.GetByIdAsync(id);
        if (notificacao is null) return;
        notificacao.Lida = true;
        _notificacaoRepository.Update(notificacao);
        await _notificacaoRepository.SaveChangesAsync();
    }
}
