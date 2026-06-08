using SaudeConectada.Domain.Entities;
using SaudeConectada.Domain.Interfaces.Repositories;
using SaudeConectada.Domain.Interfaces.Services;

namespace SaudeConectada.Service.Services;

public class PacienteService : IPacienteService
{
    private readonly IPacienteRepository _pacienteRepository;

    public PacienteService(IPacienteRepository pacienteRepository)
    {
        _pacienteRepository = pacienteRepository;
    }

    public async Task<Paciente?> GetByIdAsync(int id) =>
        await _pacienteRepository.GetByIdAsync(id);

    public async Task<IEnumerable<Paciente>> GetAllAsync() =>
        await _pacienteRepository.GetAllAsync();

    public async Task<Paciente?> GetByCpfAsync(string cpf) =>
        await _pacienteRepository.GetByCpfAsync(cpf);

    public async Task<Paciente> CreateAsync(Paciente paciente)
    {
        await _pacienteRepository.AddAsync(paciente);
        await _pacienteRepository.SaveChangesAsync();
        return paciente;
    }

    public async Task UpdateAsync(Paciente paciente)
    {
        _pacienteRepository.Update(paciente);
        await _pacienteRepository.SaveChangesAsync();
    }

    public async Task DeleteAsync(int id)
    {
        var paciente = await _pacienteRepository.GetByIdAsync(id);
        if (paciente is null) return;
        _pacienteRepository.Delete(paciente);
        await _pacienteRepository.SaveChangesAsync();
    }
}
