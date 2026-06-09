using Microsoft.EntityFrameworkCore;
using SaudeConectada.Domain.Entities;
using SaudeConectada.Domain.Interfaces.Repositories;
using SaudeConectada.Persistence.Data;

namespace SaudeConectada.Persistence.Repositories;

public class MensagemRepository : Repository<Mensagem>, IMensagemRepository
{
    public MensagemRepository(AppDbContext context) : base(context) { }

    public async Task<IEnumerable<Mensagem>> GetConversaAsync(int usuarioId, int outroUsuarioId)
        => await _dbSet.Include(m => m.Remetente).Include(m => m.Destinatario)
            .Where(m => (m.RemetenteId == usuarioId && m.DestinatarioId == outroUsuarioId)
                     || (m.RemetenteId == outroUsuarioId && m.DestinatarioId == usuarioId))
            .OrderBy(m => m.EnviadaEm).ToListAsync();

    public async Task<IEnumerable<Mensagem>> GetByDestinatarioIdAsync(int destinatarioId)
        => await _dbSet.Include(m => m.Remetente)
            .Where(m => m.DestinatarioId == destinatarioId).OrderByDescending(m => m.EnviadaEm).ToListAsync();
}
