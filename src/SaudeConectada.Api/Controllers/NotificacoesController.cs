using System.Security.Claims;
using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using SaudeConectada.Domain.DTOs.Notificacoes;
using SaudeConectada.Domain.Interfaces.Services;

namespace SaudeConectada.Api.Controllers;

[ApiController]
[Route("api/[controller]")]
[Authorize]
public class NotificacoesController(INotificacaoService notificacaoService) : ControllerBase
{
    [HttpGet]
    public async Task<ActionResult<IEnumerable<NotificacaoResponse>>> GetMinhas()
    {
        var userId = int.Parse(User.FindFirstValue(ClaimTypes.NameIdentifier)!);
        var notificacoes = await notificacaoService.GetByUsuarioAsync(userId);
        return Ok(notificacoes.Select(n => new NotificacaoResponse(n.Id, n.Tipo, n.Titulo, n.Mensagem, n.Lida, n.CriadaEm)));
    }

    [HttpGet("nao-lidas")]
    public async Task<ActionResult<IEnumerable<NotificacaoResponse>>> GetNaoLidas()
    {
        var userId = int.Parse(User.FindFirstValue(ClaimTypes.NameIdentifier)!);
        var notificacoes = await notificacaoService.GetNaoLidasAsync(userId);
        return Ok(notificacoes.Select(n => new NotificacaoResponse(n.Id, n.Tipo, n.Titulo, n.Mensagem, n.Lida, n.CriadaEm)));
    }

    [HttpPut("{id:int}/lida")]
    public async Task<IActionResult> MarcarComoLida(int id)
    {
        await notificacaoService.MarcarComoLidaAsync(id);
        return NoContent();
    }
}
