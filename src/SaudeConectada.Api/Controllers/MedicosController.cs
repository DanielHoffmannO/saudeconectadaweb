using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using SaudeConectada.Domain.DTOs.Medicos;
using SaudeConectada.Domain.Entities;
using SaudeConectada.Domain.Enums;
using SaudeConectada.Domain.Interfaces.Services;

namespace SaudeConectada.Api.Controllers;

[ApiController]
[Route("api/[controller]")]
[Authorize]
public class MedicosController(IMedicoService medicoService) : ControllerBase
{
    [HttpGet]
    public async Task<ActionResult<IEnumerable<MedicoResponse>>> GetAll([FromQuery] string? q)
    {
        var medicos = await medicoService.GetAllAsync();
        if (!string.IsNullOrWhiteSpace(q))
        {
            medicos = medicos.Where(m =>
                m.Nome.Contains(q, StringComparison.OrdinalIgnoreCase) ||
                m.Especialidade.ToString().Contains(q, StringComparison.OrdinalIgnoreCase));
        }
        return Ok(medicos.Select(ToResponse));
    }

    [HttpGet("{id:int}")]
    public async Task<ActionResult<MedicoResponse>> GetById(int id)
    {
        var medico = await medicoService.GetByIdAsync(id);
        if (medico is null) return NotFound();
        return Ok(ToResponse(medico));
    }

    [HttpGet("especialidade/{especialidade}")]
    public async Task<ActionResult<IEnumerable<MedicoResponse>>> GetByEspecialidade(Especialidade especialidade)
    {
        var medicos = await medicoService.GetByEspecialidadeAsync(especialidade);
        return Ok(medicos.Select(ToResponse));
    }

    [HttpPost]
    public async Task<ActionResult<MedicoResponse>> Create(CreateMedicoRequest request)
    {
        var medico = new Medico
        {
            Nome = request.Nome,
            CRM = request.CRM,
            Especialidade = request.Especialidade,
            Email = request.Email,
            Telefone = request.Telefone
        };

        var created = await medicoService.CreateAsync(medico);
        return CreatedAtAction(nameof(GetById), new { id = created.Id }, ToResponse(created));
    }

    [HttpPut("{id:int}")]
    public async Task<IActionResult> Update(int id, CreateMedicoRequest request)
    {
        var existing = await medicoService.GetByIdAsync(id);
        if (existing is null) return NotFound();

        existing.Nome = request.Nome;
        existing.CRM = request.CRM;
        existing.Especialidade = request.Especialidade;
        existing.Email = request.Email;
        existing.Telefone = request.Telefone;

        await medicoService.UpdateAsync(existing);
        return NoContent();
    }

    [HttpDelete("{id:int}")]
    public async Task<IActionResult> Delete(int id)
    {
        var existing = await medicoService.GetByIdAsync(id);
        if (existing is null) return NotFound();

        await medicoService.DeleteAsync(id);
        return NoContent();
    }

    private static MedicoResponse ToResponse(Medico m) =>
        new(m.Id, m.Nome, m.CRM, m.Especialidade, m.Email, m.Telefone);
}
