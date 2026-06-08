using SaudeConectada.Domain.Entities;
using SaudeConectada.Domain.Enums;

namespace SaudeConectada.Domain.Interfaces.Services;

public interface IMedicoService
{
    Task<Medico?> GetByIdAsync(int id);
    Task<IEnumerable<Medico>> GetAllAsync();
    Task<IEnumerable<Medico>> GetByEspecialidadeAsync(Especialidade especialidade);
    Task<Medico> CreateAsync(Medico medico);
    Task UpdateAsync(Medico medico);
    Task DeleteAsync(int id);
}
