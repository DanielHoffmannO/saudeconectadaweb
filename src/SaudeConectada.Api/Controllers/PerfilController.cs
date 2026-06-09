using System.Security.Claims;
using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using SaudeConectada.Domain.Interfaces.Repositories;

namespace SaudeConectada.Api.Controllers;

[ApiController]
[Route("api/[controller]")]
[Authorize]
public class PerfilController(IUsuarioRepository usuarioRepository) : ControllerBase
{
    [HttpGet]
    public async Task<IActionResult> Get()
    {
        var userId = int.Parse(User.FindFirstValue(ClaimTypes.NameIdentifier)!);
        var usuario = await usuarioRepository.GetByIdAsync(userId);
        if (usuario is null) return NotFound();
        return Ok(new { usuario.Id, usuario.Nome, usuario.Email, usuario.Role, usuario.CriadoEm });
    }

    [HttpPut]
    public async Task<IActionResult> Update([FromBody] UpdatePerfilRequest request)
    {
        var userId = int.Parse(User.FindFirstValue(ClaimTypes.NameIdentifier)!);
        var usuario = await usuarioRepository.GetByIdAsync(userId);
        if (usuario is null) return NotFound();

        usuario.Nome = request.Nome ?? usuario.Nome;
        usuario.Email = request.Email ?? usuario.Email;
        usuarioRepository.Update(usuario);
        await usuarioRepository.SaveChangesAsync();
        return NoContent();
    }
}

public record UpdatePerfilRequest(string? Nome, string? Email);
