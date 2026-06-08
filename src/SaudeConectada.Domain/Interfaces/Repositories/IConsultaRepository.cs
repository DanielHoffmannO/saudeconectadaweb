using SaudeConectada.Domain.Entities;

namespace SaudeConectada.Domain.Interfaces.Repositories;

public interface IConsultaRepository : IRepository<Consulta>
{
    Task<IEnumerable<Consulta>> GetByMedicoIdAsync(int medicoId);
    Task<IEnumerable<Consulta>> GetByPacienteIdAsync(int pacienteId);
    Task<IEnumerable<Consulta>> GetByDataAsync(DateTime data);
}
