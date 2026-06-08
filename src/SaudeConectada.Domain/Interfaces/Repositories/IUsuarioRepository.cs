using SaudeConectada.Domain.Entities;

namespace SaudeConectada.Domain.Interfaces.Repositories;

public interface IUsuarioRepository : IRepository<Usuario>
{
    Task<Usuario?> GetByEmailAsync(string email);
}
