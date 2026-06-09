using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using SaudeConectada.Domain.DTOs.Consultas;
using SaudeConectada.Domain.Entities;
using SaudeConectada.Domain.Interfaces.Services;

namespace SaudeConectada.Api.Controllers;

[ApiController]
[Route("api/[controller]")]
[Authorize]
public class ConsultasController(IConsultaService consultaService) : ControllerBase
{
    [HttpGet]
    public async Task<ActionResult<IEnumerable<ConsultaResponse>>> GetAll()
    {
        var consultas = await consultaService.GetAllAsync();
        return Ok(consultas.Select(ToResponse));
    }

    [HttpPost]
    public async Task<ActionResult<ConsultaResponse>> Agendar(AgendarConsultaRequest request)
    {
        var consulta = new Consulta
        {
            MedicoId = request.MedicoId,
            PacienteId = request.PacienteId,
            DataHora = request.DataHora,
            Observacoes = request.Observacoes
        };

        try
        {
            var created = await consultaService.AgendarAsync(consulta);
            return Created(string.Empty, ToResponse(created));
        }
        catch (InvalidOperationException ex)
        {
            return BadRequest(ex.Message);
        }
    }

    [HttpPut("cancelar/{id:int}")]
    public async Task<IActionResult> Cancelar(int id)
    {
        try
        {
            await consultaService.CancelarAsync(id);
            return NoContent();
        }
        catch (InvalidOperationException ex)
        {
            return NotFound(ex.Message);
        }
    }

    [HttpGet("por-medico/{medicoId:int}")]
    public async Task<ActionResult<IEnumerable<ConsultaResponse>>> GetByMedico(int medicoId)
    {
        var consultas = await consultaService.GetByMedicoAsync(medicoId);
        return Ok(consultas.Select(ToResponse));
    }

    [HttpGet("por-paciente/{pacienteId:int}")]
    public async Task<ActionResult<IEnumerable<ConsultaResponse>>> GetByPaciente(int pacienteId)
    {
        var consultas = await consultaService.GetByPacienteAsync(pacienteId);
        return Ok(consultas.Select(ToResponse));
    }

    private static ConsultaResponse ToResponse(Consulta c) =>
        new(c.Id, c.MedicoId, c.Medico?.Nome ?? string.Empty, c.PacienteId, c.Paciente?.Nome ?? string.Empty, c.DataHora, c.Status, c.Observacoes);
}
