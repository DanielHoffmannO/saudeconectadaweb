using Microsoft.EntityFrameworkCore;
using SaudeConectada.Domain.Entities;
using SaudeConectada.Domain.Enums;
using SaudeConectada.Domain.Interfaces.Repositories;
using SaudeConectada.Persistence.Data;

namespace SaudeConectada.Persistence.Repositories;

public class ExameRepository : Repository<Exame>, IExameRepository
{
    public ExameRepository(AppDbContext context) : base(context) { }

    public async Task<IEnumerable<Exame>> GetByPacienteIdAsync(int pacienteId)
        => await _dbSet.Include(e => e.Medico).Include(e => e.Paciente)
            .Where(e => e.PacienteId == pacienteId).ToListAsync();

    public async Task<IEnumerable<Exame>> GetByStatusAsync(StatusExame status)
        => await _dbSet.Include(e => e.Medico).Include(e => e.Paciente)
            .Where(e => e.Status == status).ToListAsync();
}
