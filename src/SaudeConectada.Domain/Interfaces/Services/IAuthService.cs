namespace SaudeConectada.Domain.Interfaces.Services;

public interface IAuthService
{
    Task<string?> RegistrarAsync(string nome, string email, string senha, string role);
    Task<string?> LoginAsync(string email, string senha);
}
