using System.IdentityModel.Tokens.Jwt;
using System.Security.Claims;
using System.Security.Cryptography;
using System.Text;
using Microsoft.Extensions.Configuration;
using Microsoft.IdentityModel.Tokens;
using SaudeConectada.Domain.Entities;
using SaudeConectada.Domain.Interfaces.Repositories;
using SaudeConectada.Domain.Interfaces.Services;

namespace SaudeConectada.Service.Services;

public class AuthService : IAuthService
{
    private readonly IUsuarioRepository _usuarioRepository;
    private readonly IConfiguration _configuration;

    public AuthService(IUsuarioRepository usuarioRepository, IConfiguration configuration)
    {
        _usuarioRepository = usuarioRepository;
        _configuration = configuration;
    }

    public async Task<string?> RegistrarAsync(string nome, string email, string senha, string role)
    {
        var existente = await _usuarioRepository.GetByEmailAsync(email);
        if (existente is not null) return null;

        var usuario = new Usuario
        {
            Nome = nome,
            Email = email,
            SenhaHash = HashSenha(senha),
            Role = role,
            CriadoEm = DateTime.UtcNow
        };

        await _usuarioRepository.AddAsync(usuario);
        await _usuarioRepository.SaveChangesAsync();

        return GerarToken(usuario);
    }

    public async Task<string?> LoginAsync(string email, string senha)
    {
        var usuario = await _usuarioRepository.GetByEmailAsync(email);
        if (usuario is null || usuario.SenhaHash != HashSenha(senha)) return null;

        return GerarToken(usuario);
    }

    private static string HashSenha(string senha)
    {
        var bytes = SHA256.HashData(Encoding.UTF8.GetBytes(senha));
        return Convert.ToBase64String(bytes);
    }

    private string GerarToken(Usuario usuario)
    {
        var key = new SymmetricSecurityKey(Encoding.UTF8.GetBytes(_configuration["Jwt:Key"]!));
        var claims = new[]
        {
            new Claim(JwtRegisteredClaimNames.Sub, usuario.Id.ToString()),
            new Claim(JwtRegisteredClaimNames.Email, usuario.Email),
            new Claim(ClaimTypes.Role, usuario.Role)
        };

        var token = new JwtSecurityToken(
            issuer: _configuration["Jwt:Issuer"],
            audience: _configuration["Jwt:Audience"],
            claims: claims,
            expires: DateTime.UtcNow.AddHours(24),
            signingCredentials: new SigningCredentials(key, SecurityAlgorithms.HmacSha256)
        );

        return new JwtSecurityTokenHandler().WriteToken(token);
    }
}
