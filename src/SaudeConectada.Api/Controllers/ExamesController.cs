using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using SaudeConectada.Domain.DTOs.Exames;
using SaudeConectada.Domain.Entities;
using SaudeConectada.Domain.Enums;
using SaudeConectada.Domain.Interfaces.Services;

namespace SaudeConectada.Api.Controllers;

[ApiController]
[Route("api/[controller]")]
[Authorize]
public class ExamesController(IExameService exameService) : ControllerBase
{
    [HttpGet]
    public async Task<ActionResult<IEnumerable<ExameResponse>>> GetAll()
    {
        var exames = await exameService.GetAllAsync();
        return Ok(exames.Select(ToResponse));
    }

    [HttpGet("paciente/{pacienteId:int}")]
    public async Task<ActionResult<IEnumerable<ExameResponse>>> GetByPaciente(int pacienteId)
    {
        var exames = await exameService.GetByPacienteAsync(pacienteId);
        return Ok(exames.Select(ToResponse));
    }

    [HttpGet("status/{status}")]
    public async Task<ActionResult<IEnumerable<ExameResponse>>> GetByStatus(StatusExame status)
    {
        var exames = await exameService.GetByStatusAsync(status);
        return Ok(exames.Select(ToResponse));
    }

    [HttpPost]
    public async Task<ActionResult<ExameResponse>> Solicitar(CreateExameRequest request)
    {
        var exame = new Exame
        {
            PacienteId = request.PacienteId,
            MedicoId = request.MedicoId,
            Tipo = request.Tipo,
            Laboratorio = request.Laboratorio,
            Observacoes = request.Observacoes
        };

        var created = await exameService.SolicitarAsync(exame);
        return Created(string.Empty, ToResponse(created));
    }

    private static ExameResponse ToResponse(Exame e) =>
        new(e.Id, e.PacienteId, e.Paciente?.Nome ?? "", e.MedicoId, e.Medico?.Nome ?? "", e.Tipo, e.Laboratorio, e.DataSolicitacao, e.DataRealizacao, e.Status, e.Resultado, e.Observacoes);
}
