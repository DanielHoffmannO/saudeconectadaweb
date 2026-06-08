using Microsoft.EntityFrameworkCore;
using SaudeConectada.Domain.Entities;
using SaudeConectada.Domain.Enums;
using SaudeConectada.Domain.Interfaces.Repositories;
using SaudeConectada.Persistence.Data;

namespace SaudeConectada.Persistence.Repositories;

public class MedicoRepository : Repository<Medico>, IMedicoRepository
{
    public MedicoRepository(AppDbContext context) : base(context) { }

    public async Task<IEnumerable<Medico>> GetByEspecialidadeAsync(Especialidade especialidade)
        => await _dbSet.Where(m => m.Especialidade == especialidade).ToListAsync();
}
