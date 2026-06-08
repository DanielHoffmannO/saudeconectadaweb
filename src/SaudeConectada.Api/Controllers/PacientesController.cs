using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using SaudeConectada.Domain.DTOs.Pacientes;
using SaudeConectada.Domain.Entities;
using SaudeConectada.Domain.Interfaces.Services;

namespace SaudeConectada.Api.Controllers;

[ApiController]
[Route("api/[controller]")]
[Authorize]
public class PacientesController(IPacienteService pacienteService) : ControllerBase
{
    [HttpGet]
    public async Task<ActionResult<IEnumerable<PacienteResponse>>> GetAll()
    {
        var pacientes = await pacienteService.GetAllAsync();
        return Ok(pacientes.Select(ToResponse));
    }

    [HttpGet("{id:int}")]
    public async Task<ActionResult<PacienteResponse>> GetById(int id)
    {
        var paciente = await pacienteService.GetByIdAsync(id);
        if (paciente is null) return NotFound();
        return Ok(ToResponse(paciente));
    }

    [HttpPost]
    public async Task<ActionResult<PacienteResponse>> Create(CreatePacienteRequest request)
    {
        var paciente = new Paciente
        {
            Nome = request.Nome,
            CPF = request.CPF,
            Email = request.Email,
            Telefone = request.Telefone,
            DataNascimento = request.DataNascimento
        };

        var created = await pacienteService.CreateAsync(paciente);
        return CreatedAtAction(nameof(GetById), new { id = created.Id }, ToResponse(created));
    }

    [HttpPut("{id:int}")]
    public async Task<IActionResult> Update(int id, CreatePacienteRequest request)
    {
        var existing = await pacienteService.GetByIdAsync(id);
        if (existing is null) return NotFound();

        existing.Nome = request.Nome;
        existing.CPF = request.CPF;
        existing.Email = request.Email;
        existing.Telefone = request.Telefone;
        existing.DataNascimento = request.DataNascimento;

        await pacienteService.UpdateAsync(existing);
        return NoContent();
    }

    [HttpDelete("{id:int}")]
    public async Task<IActionResult> Delete(int id)
    {
        var existing = await pacienteService.GetByIdAsync(id);
        if (existing is null) return NotFound();

        await pacienteService.DeleteAsync(id);
        return NoContent();
    }

    private static PacienteResponse ToResponse(Paciente p) =>
        new(p.Id, p.Nome, p.CPF, p.Email, p.Telefone, p.DataNascimento);
}
