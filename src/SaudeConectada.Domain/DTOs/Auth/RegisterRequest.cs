namespace SaudeConectada.Domain.DTOs.Auth;

public record RegisterRequest(string Nome, string Email, string Senha, string Role);
