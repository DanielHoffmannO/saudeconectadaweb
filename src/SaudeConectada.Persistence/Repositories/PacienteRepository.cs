using Microsoft.EntityFrameworkCore;
using SaudeConectada.Domain.Entities;
using SaudeConectada.Domain.Interfaces.Repositories;
using SaudeConectada.Persistence.Data;

namespace SaudeConectada.Persistence.Repositories;

public class PacienteRepository : Repository<Paciente>, IPacienteRepository
{
    public PacienteRepository(AppDbContext context) : base(context) { }

    public async Task<Paciente?> GetByCpfAsync(string cpf)
        => await _dbSet.FirstOrDefaultAsync(p => p.CPF == cpf);
}
