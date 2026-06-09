using SaudeConectada.Domain.Entities;
using SaudeConectada.Domain.Enums;

namespace SaudeConectada.Domain.Interfaces.Services;

public interface IExameService
{
    Task<Exame> SolicitarAsync(Exame exame);
    Task<IEnumerable<Exame>> GetByPacienteAsync(int pacienteId);
    Task<IEnumerable<Exame>> GetByStatusAsync(StatusExame status);
    Task<IEnumerable<Exame>> GetAllAsync();
}
