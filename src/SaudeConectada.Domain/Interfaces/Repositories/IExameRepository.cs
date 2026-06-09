using SaudeConectada.Domain.Entities;
using SaudeConectada.Domain.Enums;

namespace SaudeConectada.Domain.Interfaces.Repositories;

public interface IExameRepository : IRepository<Exame>
{
    Task<IEnumerable<Exame>> GetByPacienteIdAsync(int pacienteId);
    Task<IEnumerable<Exame>> GetByStatusAsync(StatusExame status);
}
