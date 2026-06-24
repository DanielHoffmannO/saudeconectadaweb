using Microsoft.EntityFrameworkCore;
using SaudeConectada.Domain.Entities;
using SaudeConectada.Persistence.Data;
using SaudeConectada.Persistence.Repositories;
using SaudeConectada.Service.Services;

namespace SaudeConectada.Tests;

public class MensagemServiceTests
{
    private AppDbContext CreateContext()
    {
        var options = new DbContextOptionsBuilder<AppDbContext>()
            .UseInMemoryDatabase(Guid.NewGuid().ToString())
            .Options;
        var ctx = new AppDbContext(options);
        ctx.Usuarios.Add(new Usuario { Id = 1, Nome = "Dr. Ana", Email = "ana@t.com", SenhaHash = "x", Role = "medico" });
        ctx.Usuarios.Add(new Usuario { Id = 2, Nome = "João", Email = "joao@t.com", SenhaHash = "x", Role = "paciente" });
        ctx.SaveChanges();
        return ctx;
    }

    [Fact]
    public async Task Enviar_DeveCriarMensagemComDataELidaFalse()
    {
        var ctx = CreateContext();
        var service = new MensagemService(new MensagemRepository(ctx));

        var msg = new Mensagem { RemetenteId = 1, DestinatarioId = 2, Conteudo = "Olá, João!" };
        var result = await service.EnviarAsync(msg);

        Assert.True(result.Id > 0);
        Assert.False(result.Lida);
        Assert.Equal("Olá, João!", result.Conteudo);
        Assert.True(result.EnviadaEm <= DateTime.UtcNow);
    }

    [Fact]
    public async Task GetConversa_DeveRetornarMensagensEntreUsuarios()
    {
        var ctx = CreateContext();
        var service = new MensagemService(new MensagemRepository(ctx));
        await service.EnviarAsync(new Mensagem { RemetenteId = 1, DestinatarioId = 2, Conteudo = "Msg1" });
        await service.EnviarAsync(new Mensagem { RemetenteId = 2, DestinatarioId = 1, Conteudo = "Msg2" });
        await service.EnviarAsync(new Mensagem { RemetenteId = 1, DestinatarioId = 2, Conteudo = "Msg3" });

        var conversa = await service.GetConversaAsync(1, 2);

        Assert.Equal(3, conversa.Count());
    }

    [Fact]
    public async Task GetRecebidos_DeveRetornarApenasMensagensParaDestinatario()
    {
        var ctx = CreateContext();
        var service = new MensagemService(new MensagemRepository(ctx));
        await service.EnviarAsync(new Mensagem { RemetenteId = 1, DestinatarioId = 2, Conteudo = "Para João" });
        await service.EnviarAsync(new Mensagem { RemetenteId = 2, DestinatarioId = 1, Conteudo = "Para Ana" });

        var recebidosJoao = await service.GetRecebidosAsync(2);
        var recebidosAna = await service.GetRecebidosAsync(1);

        Assert.Single(recebidosJoao);
        Assert.Single(recebidosAna);
    }

    [Fact]
    public async Task GetConversa_NaoDeveRetornarMensagensDeOutrosUsuarios()
    {
        var ctx = CreateContext();
        ctx.Usuarios.Add(new Usuario { Id = 3, Nome = "Pedro", Email = "pedro@t.com", SenhaHash = "x", Role = "paciente" });
        ctx.SaveChanges();
        var service = new MensagemService(new MensagemRepository(ctx));
        await service.EnviarAsync(new Mensagem { RemetenteId = 1, DestinatarioId = 2, Conteudo = "Ana→João" });
        await service.EnviarAsync(new Mensagem { RemetenteId = 1, DestinatarioId = 3, Conteudo = "Ana→Pedro" });

        var conversa = await service.GetConversaAsync(1, 2);

        Assert.Single(conversa);
        Assert.Equal("Ana→João", conversa.First().Conteudo);
    }
}
