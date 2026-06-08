using SaudeConectada.Domain.Entities;

namespace SaudeConectada.Domain.Interfaces.Repositories;

public interface IPacienteRepository : IRepository<Paciente>
{
    Task<Paciente?> GetByCpfAsync(string cpf);
}
