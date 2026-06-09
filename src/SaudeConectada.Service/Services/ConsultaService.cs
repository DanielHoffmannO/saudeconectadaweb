using SaudeConectada.Domain.Entities;
using SaudeConectada.Domain.Enums;
using SaudeConectada.Domain.Interfaces.Repositories;
using SaudeConectada.Domain.Interfaces.Services;

namespace SaudeConectada.Service.Services;

public class ConsultaService : IConsultaService
{
    private readonly IConsultaRepository _consultaRepository;
    private readonly IMedicoRepository _medicoRepository;
    private readonly IPacienteRepository _pacienteRepository;

    public ConsultaService(
        IConsultaRepository consultaRepository,
        IMedicoRepository medicoRepository,
        IPacienteRepository pacienteRepository)
    {
        _consultaRepository = consultaRepository;
        _medicoRepository = medicoRepository;
        _pacienteRepository = pacienteRepository;
    }

    public async Task<Consulta> AgendarAsync(Consulta consulta)
    {
        var medico = await _medicoRepository.GetByIdAsync(consulta.MedicoId)
            ?? throw new InvalidOperationException("Médico não encontrado.");

        var paciente = await _pacienteRepository.GetByIdAsync(consulta.PacienteId)
            ?? throw new InvalidOperationException("Paciente não encontrado.");

        var consultasDoMedico = await _consultaRepository.GetByMedicoIdAsync(consulta.MedicoId);
        var conflito = consultasDoMedico.Any(c =>
            c.DataHora == consulta.DataHora && c.Status != StatusConsulta.Cancelada);

        if (conflito)
            throw new InvalidOperationException("Médico já possui consulta neste horário.");

        consulta.Status = StatusConsulta.Agendada;

        await _consultaRepository.AddAsync(consulta);
        await _consultaRepository.SaveChangesAsync();

        return consulta;
    }

    public async Task CancelarAsync(int id)
    {
        var consulta = await _consultaRepository.GetByIdAsync(id)
            ?? throw new InvalidOperationException("Consulta não encontrada.");

        consulta.Status = StatusConsulta.Cancelada;
        _consultaRepository.Update(consulta);
        await _consultaRepository.SaveChangesAsync();
    }

    public async Task<IEnumerable<Consulta>> GetByMedicoAsync(int medicoId) =>
        await _consultaRepository.GetByMedicoIdAsync(medicoId);

    public async Task<IEnumerable<Consulta>> GetByPacienteAsync(int pacienteId) =>
        await _consultaRepository.GetByPacienteIdAsync(pacienteId);

    public async Task<IEnumerable<Consulta>> GetAllAsync() =>
        await _consultaRepository.GetAllAsync();
}
