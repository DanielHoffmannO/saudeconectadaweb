using SaudeConectada.Domain.Entities;
using SaudeConectada.Domain.Enums;

namespace SaudeConectada.Domain.Interfaces.Repositories;

public interface IMedicoRepository : IRepository<Medico>
{
    Task<IEnumerable<Medico>> GetByEspecialidadeAsync(Especialidade especialidade);
}
