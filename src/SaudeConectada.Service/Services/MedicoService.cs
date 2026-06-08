using SaudeConectada.Domain.Entities;
using SaudeConectada.Domain.Enums;
using SaudeConectada.Domain.Interfaces.Repositories;
using SaudeConectada.Domain.Interfaces.Services;

namespace SaudeConectada.Service.Services;

public class MedicoService : IMedicoService
{
    private readonly IMedicoRepository _medicoRepository;

    public MedicoService(IMedicoRepository medicoRepository)
    {
        _medicoRepository = medicoRepository;
    }

    public async Task<Medico?> GetByIdAsync(int id) =>
        await _medicoRepository.GetByIdAsync(id);

    public async Task<IEnumerable<Medico>> GetAllAsync() =>
        await _medicoRepository.GetAllAsync();

    public async Task<IEnumerable<Medico>> GetByEspecialidadeAsync(Especialidade especialidade) =>
        await _medicoRepository.GetByEspecialidadeAsync(especialidade);

    public async Task<Medico> CreateAsync(Medico medico)
    {
        await _medicoRepository.AddAsync(medico);
        await _medicoRepository.SaveChangesAsync();
        return medico;
    }

    public async Task UpdateAsync(Medico medico)
    {
        _medicoRepository.Update(medico);
        await _medicoRepository.SaveChangesAsync();
    }

    public async Task DeleteAsync(int id)
    {
        var medico = await _medicoRepository.GetByIdAsync(id);
        if (medico is null) return;
        _medicoRepository.Delete(medico);
        await _medicoRepository.SaveChangesAsync();
    }
}
