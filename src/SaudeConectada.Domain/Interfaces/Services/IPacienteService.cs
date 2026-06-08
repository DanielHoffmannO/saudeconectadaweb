using SaudeConectada.Domain.Entities;

namespace SaudeConectada.Domain.Interfaces.Services;

public interface IPacienteService
{
    Task<Paciente?> GetByIdAsync(int id);
    Task<IEnumerable<Paciente>> GetAllAsync();
    Task<Paciente?> GetByCpfAsync(string cpf);
    Task<Paciente> CreateAsync(Paciente paciente);
    Task UpdateAsync(Paciente paciente);
    Task DeleteAsync(int id);
}
