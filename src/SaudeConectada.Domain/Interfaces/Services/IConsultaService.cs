using SaudeConectada.Domain.Entities;

namespace SaudeConectada.Domain.Interfaces.Services;

public interface IConsultaService
{
    Task<Consulta> AgendarAsync(Consulta consulta);
    Task CancelarAsync(int id);
    Task<IEnumerable<Consulta>> GetByMedicoAsync(int medicoId);
    Task<IEnumerable<Consulta>> GetByPacienteAsync(int pacienteId);
}
