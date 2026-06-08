using Microsoft.EntityFrameworkCore;
using SaudeConectada.Domain.Entities;
using SaudeConectada.Domain.Enums;
using SaudeConectada.Persistence.Data;
using SaudeConectada.Persistence.Repositories;
using SaudeConectada.Service.Services;

namespace SaudeConectada.Tests;

public class MedicoServiceTests
{
    private AppDbContext CreateContext()
    {
        var options = new DbContextOptionsBuilder<AppDbContext>()
            .UseInMemoryDatabase(Guid.NewGuid().ToString())
            .Options;
        return new AppDbContext(options);
    }

    [Fact]
    public async Task Create_DeveAdicionarMedico()
    {
        var ctx = CreateContext();
        var service = new MedicoService(new MedicoRepository(ctx));

        var medico = new Medico { Nome = "Dr. Carlos", CRM = "99999", Especialidade = Especialidade.Neurologia, Email = "carlos@test.com", Telefone = "11777" };
        var result = await service.CreateAsync(medico);

        Assert.True(result.Id > 0);
        Assert.Equal("Dr. Carlos", result.Nome);
    }

    [Fact]
    public async Task GetByEspecialidade_DeveRetornarFiltrado()
    {
        var ctx = CreateContext();
        var repo = new MedicoRepository(ctx);
        await repo.AddAsync(new Medico { Nome = "A", CRM = "1", Especialidade = Especialidade.Pediatria, Email = "a@t.com", Telefone = "1" });
        await repo.AddAsync(new Medico { Nome = "B", CRM = "2", Especialidade = Especialidade.Ortopedia, Email = "b@t.com", Telefone = "2" });
        await repo.SaveChangesAsync();

        var service = new MedicoService(repo);
        var result = await service.GetByEspecialidadeAsync(Especialidade.Pediatria);

        Assert.Single(result);
    }

    [Fact]
    public async Task Delete_DeveRemoverMedico()
    {
        var ctx = CreateContext();
        var service = new MedicoService(new MedicoRepository(ctx));

        var medico = await service.CreateAsync(new Medico { Nome = "X", CRM = "3", Especialidade = Especialidade.Dermatologia, Email = "x@t.com", Telefone = "3" });
        await service.DeleteAsync(medico.Id);

        Assert.Null(await service.GetByIdAsync(medico.Id));
    }
}
