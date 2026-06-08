using System.Text.Json.Serialization;

namespace SaudeConectada.Domain.DTOs.Auth;

public class LoginRequest
{
    public string Email { get; set; } = string.Empty;

    [JsonPropertyName("senha")]
    public string Senha { get; set; } = string.Empty;

    [JsonPropertyName("password")]
    public string? Password { set => Senha = value ?? Senha; get => null; }
}
