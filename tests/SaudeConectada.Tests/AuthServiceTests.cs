using Microsoft.EntityFrameworkCore;
using Microsoft.Extensions.Configuration;
using SaudeConectada.Persistence.Data;
using SaudeConectada.Persistence.Repositories;
using SaudeConectada.Service.Services;

namespace SaudeConectada.Tests;

public class AuthServiceTests
{
    private (AppDbContext ctx, AuthService service) Setup()
    {
        var options = new DbContextOptionsBuilder<AppDbContext>()
            .UseInMemoryDatabase(Guid.NewGuid().ToString())
            .Options;
        var ctx = new AppDbContext(options);

        var config = new ConfigurationBuilder()
            .AddInMemoryCollection(new Dictionary<string, string?>
            {
                ["Jwt:Key"] = "TestKey-SuperSecret-MinLength32-Chars!!",
                ["Jwt:Issuer"] = "Test",
                ["Jwt:Audience"] = "Test"
            })
            .Build();

        return (ctx, new AuthService(new UsuarioRepository(ctx), config));
    }

    [Fact]
    public async Task Registrar_DeveRetornarToken()
    {
        var (_, service) = Setup();
        var token = await service.RegistrarAsync("Ana", "ana@test.com", "senha123", "admin");

        Assert.NotNull(token);
        Assert.Contains(".", token);
    }

    [Fact]
    public async Task Registrar_DeveRetornarNull_QuandoEmailDuplicado()
    {
        var (_, service) = Setup();
        await service.RegistrarAsync("Ana", "ana@test.com", "senha123", "admin");
        var result = await service.RegistrarAsync("Ana2", "ana@test.com", "outra", "admin");

        Assert.Null(result);
    }

    [Fact]
    public async Task Login_DeveRetornarToken_QuandoCredenciaisCorretas()
    {
        var (_, service) = Setup();
        await service.RegistrarAsync("Ana", "ana@test.com", "senha123", "admin");
        var token = await service.LoginAsync("ana@test.com", "senha123");

        Assert.NotNull(token);
    }

    [Fact]
    public async Task Login_DeveRetornarNull_QuandoSenhaErrada()
    {
        var (_, service) = Setup();
        await service.RegistrarAsync("Ana", "ana@test.com", "senha123", "admin");
        var token = await service.LoginAsync("ana@test.com", "senhaErrada");

        Assert.Null(token);
    }
}
