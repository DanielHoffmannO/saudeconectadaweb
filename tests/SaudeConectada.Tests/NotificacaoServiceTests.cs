using Microsoft.EntityFrameworkCore;
using SaudeConectada.Domain.Entities;
using SaudeConectada.Domain.Enums;
using SaudeConectada.Persistence.Data;
using SaudeConectada.Persistence.Repositories;
using SaudeConectada.Service.Services;

namespace SaudeConectada.Tests;

public class NotificacaoServiceTests
{
    private AppDbContext CreateContext()
    {
        var options = new DbContextOptionsBuilder<AppDbContext>()
            .UseInMemoryDatabase(Guid.NewGuid().ToString())
            .Options;
        var ctx = new AppDbContext(options);
        ctx.Usuarios.Add(new Usuario { Id = 1, Nome = "User", Email = "u@t.com", SenhaHash = "x", Role = "paciente" });
        ctx.SaveChanges();
        return ctx;
    }

    private static Notificacao CriarNotificacao(int usuarioId, bool lida = false) => new()
    {
        UsuarioId = usuarioId,
        Tipo = TipoNotificacao.Consulta,
        Titulo = "Consulta agendada",
        Mensagem = "Sua consulta foi marcada.",
        Lida = lida,
        CriadaEm = DateTime.UtcNow
    };

    [Fact]
    public async Task GetByUsuario_DeveRetornarNotificacoesDoUsuario()
    {
        var ctx = CreateContext();
        var repo = new NotificacaoRepository(ctx);
        await repo.AddAsync(CriarNotificacao(1));
        await repo.AddAsync(CriarNotificacao(1));
        await repo.SaveChangesAsync();
        var service = new NotificacaoService(repo);

        var result = await service.GetByUsuarioAsync(1);

        Assert.Equal(2, result.Count());
    }

    [Fact]
    public async Task GetNaoLidas_DeveRetornarApenasNaoLidas()
    {
        var ctx = CreateContext();
        var repo = new NotificacaoRepository(ctx);
        await repo.AddAsync(CriarNotificacao(1, lida: false));
        await repo.AddAsync(CriarNotificacao(1, lida: true));
        await repo.AddAsync(CriarNotificacao(1, lida: false));
        await repo.SaveChangesAsync();
        var service = new NotificacaoService(repo);

        var result = await service.GetNaoLidasAsync(1);

        Assert.Equal(2, result.Count());
    }

    [Fact]
    public async Task MarcarComoLida_DeveAlterarFlag()
    {
        var ctx = CreateContext();
        var repo = new NotificacaoRepository(ctx);
        var notif = CriarNotificacao(1);
        await repo.AddAsync(notif);
        await repo.SaveChangesAsync();
        var service = new NotificacaoService(repo);

        await service.MarcarComoLidaAsync(notif.Id);

        var atualizada = await ctx.Notificacoes.FindAsync(notif.Id);
        Assert.True(atualizada!.Lida);
    }
}
