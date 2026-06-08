using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using SaudeConectada.Domain.DTOs.Auth;
using SaudeConectada.Domain.Interfaces.Services;

namespace SaudeConectada.Api.Controllers;

[ApiController]
[Route("api/[controller]")]
[AllowAnonymous]
public class AuthController(IAuthService authService) : ControllerBase
{
    [HttpPost("login")]
    public async Task<ActionResult<AuthResponse>> Login(LoginRequest request)
    {
        var token = await authService.LoginAsync(request.Email, request.Senha);
        if (token is null)
            return BadRequest("Email ou senha inválidos.");

        return Ok(new AuthResponse(token));
    }

    [HttpPost("register")]
    public async Task<ActionResult<AuthResponse>> Register(RegisterRequest request)
    {
        var token = await authService.RegistrarAsync(request.Nome, request.Email, request.Senha, request.Role);
        if (token is null)
            return BadRequest("Email já cadastrado.");

        return Created(string.Empty, new AuthResponse(token));
    }
}
