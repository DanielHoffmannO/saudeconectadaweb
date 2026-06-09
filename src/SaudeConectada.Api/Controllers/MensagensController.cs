using System.Security.Claims;
using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using SaudeConectada.Domain.DTOs.Mensagens;
using SaudeConectada.Domain.Entities;
using SaudeConectada.Domain.Interfaces.Services;

namespace SaudeConectada.Api.Controllers;

[ApiController]
[Route("api/[controller]")]
[Authorize]
public class MensagensController(IMensagemService mensagemService) : ControllerBase
{
    [HttpGet("conversa/{outroUsuarioId:int}")]
    public async Task<ActionResult<IEnumerable<MensagemResponse>>> GetConversa(int outroUsuarioId)
    {
        var userId = int.Parse(User.FindFirstValue(ClaimTypes.NameIdentifier)!);
        var mensagens = await mensagemService.GetConversaAsync(userId, outroUsuarioId);
        return Ok(mensagens.Select(ToResponse));
    }

    [HttpGet("recebidas")]
    public async Task<ActionResult<IEnumerable<MensagemResponse>>> GetRecebidas()
    {
        var userId = int.Parse(User.FindFirstValue(ClaimTypes.NameIdentifier)!);
        var mensagens = await mensagemService.GetRecebidosAsync(userId);
        return Ok(mensagens.Select(ToResponse));
    }

    [HttpPost]
    public async Task<ActionResult<MensagemResponse>> Enviar(EnviarMensagemRequest request)
    {
        var userId = int.Parse(User.FindFirstValue(ClaimTypes.NameIdentifier)!);
        var mensagem = new Mensagem
        {
            RemetenteId = userId,
            DestinatarioId = request.DestinatarioId,
            Conteudo = request.Conteudo
        };

        var created = await mensagemService.EnviarAsync(mensagem);
        return Created(string.Empty, ToResponse(created));
    }

    private static MensagemResponse ToResponse(Mensagem m) =>
        new(m.Id, m.RemetenteId, m.Remetente?.Nome ?? "", m.DestinatarioId, m.Destinatario?.Nome ?? "", m.Conteudo, m.EnviadaEm, m.Lida);
}
