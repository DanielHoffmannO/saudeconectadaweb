using Microsoft.EntityFrameworkCore;
using SaudeConectada.Domain.Entities;
using SaudeConectada.Domain.Interfaces.Repositories;
using SaudeConectada.Persistence.Data;

namespace SaudeConectada.Persistence.Repositories;

public class ConsultaRepository : Repository<Consulta>, IConsultaRepository
{
    public ConsultaRepository(AppDbContext context) : base(context) { }

    public async Task<IEnumerable<Consulta>> GetByMedicoIdAsync(int medicoId)
        => await _dbSet.Where(c => c.MedicoId == medicoId).ToListAsync();

    public async Task<IEnumerable<Consulta>> GetByPacienteIdAsync(int pacienteId)
        => await _dbSet.Where(c => c.PacienteId == pacienteId).ToListAsync();

    public async Task<IEnumerable<Consulta>> GetByDataAsync(DateTime data)
        => await _dbSet.Where(c => c.DataHora.Date == data.Date).ToListAsync();
}
