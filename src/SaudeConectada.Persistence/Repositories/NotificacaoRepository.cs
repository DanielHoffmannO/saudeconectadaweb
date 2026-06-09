using Microsoft.EntityFrameworkCore;
using SaudeConectada.Domain.Entities;
using SaudeConectada.Domain.Interfaces.Repositories;
using SaudeConectada.Persistence.Data;

namespace SaudeConectada.Persistence.Repositories;

public class NotificacaoRepository : Repository<Notificacao>, INotificacaoRepository
{
    public NotificacaoRepository(AppDbContext context) : base(context) { }

    public async Task<IEnumerable<Notificacao>> GetByUsuarioIdAsync(int usuarioId)
        => await _dbSet.Where(n => n.UsuarioId == usuarioId).OrderByDescending(n => n.CriadaEm).ToListAsync();

    public async Task<IEnumerable<Notificacao>> GetNaoLidasAsync(int usuarioId)
        => await _dbSet.Where(n => n.UsuarioId == usuarioId && !n.Lida).OrderByDescending(n => n.CriadaEm).ToListAsync();
}
