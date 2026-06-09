using SaudeConectada.Domain.Entities;
using SaudeConectada.Domain.Enums;
using SaudeConectada.Domain.Interfaces.Repositories;
using SaudeConectada.Domain.Interfaces.Services;

namespace SaudeConectada.Service.Services;

public class ExameService : IExameService
{
    private readonly IExameRepository _exameRepository;

    public ExameService(IExameRepository exameRepository)
    {
        _exameRepository = exameRepository;
    }

    public async Task<Exame> SolicitarAsync(Exame exame)
    {
        exame.DataSolicitacao = DateTime.UtcNow;
        exame.Status = StatusExame.Pendente;
        await _exameRepository.AddAsync(exame);
        await _exameRepository.SaveChangesAsync();
        return exame;
    }

    public async Task<IEnumerable<Exame>> GetByPacienteAsync(int pacienteId)
        => await _exameRepository.GetByPacienteIdAsync(pacienteId);

    public async Task<IEnumerable<Exame>> GetByStatusAsync(StatusExame status)
        => await _exameRepository.GetByStatusAsync(status);

    public async Task<IEnumerable<Exame>> GetAllAsync()
        => await _exameRepository.GetAllAsync();
}
