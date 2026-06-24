using Microsoft.EntityFrameworkCore;
using SaudeConectada.Domain.Entities;
using SaudeConectada.Domain.Enums;
using SaudeConectada.Persistence.Data;
using SaudeConectada.Persistence.Repositories;
using SaudeConectada.Service.Services;

namespace SaudeConectada.Tests;

public class ExameServiceTests
{
    private AppDbContext CreateContext()
    {
        var options = new DbContextOptionsBuilder<AppDbContext>()
            .UseInMemoryDatabase(Guid.NewGuid().ToString())
            .Options;
        var ctx = new AppDbContext(options);
        ctx.Medicos.Add(new Medico { Id = 1, Nome = "Dr. Ana", CRM = "12345", Especialidade = Especialidade.Cardiologia, Email = "ana@test.com", Telefone = "11999" });
        ctx.Pacientes.Add(new Paciente { Id = 1, Nome = "João", CPF = "111.222.333-44", Email = "joao@test.com", Telefone = "11888", DataNascimento = new DateTime(1990, 1, 1) });
        ctx.SaveChanges();
        return ctx;
    }

    [Fact]
    public async Task Solicitar_DeveCriarExameComStatusPendente()
    {
        var ctx = CreateContext();
        var service = new ExameService(new ExameRepository(ctx));

        var exame = new Exame { PacienteId = 1, MedicoId = 1, Tipo = "Hemograma", Laboratorio = "LabX" };
        var result = await service.SolicitarAsync(exame);

        Assert.True(result.Id > 0);
        Assert.Equal(StatusExame.Pendente, result.Status);
        Assert.Equal("Hemograma", result.Tipo);
    }

    [Fact]
    public async Task Solicitar_DeveDefinirDataSolicitacao()
    {
        var ctx = CreateContext();
        var service = new ExameService(new ExameRepository(ctx));

        var antes = DateTime.UtcNow;
        var exame = await service.SolicitarAsync(new Exame { PacienteId = 1, MedicoId = 1, Tipo = "RX", Laboratorio = "Lab" });

        Assert.True(exame.DataSolicitacao >= antes);
    }

    [Fact]
    public async Task GetByPaciente_DeveRetornarExamesDoPaciente()
    {
        var ctx = CreateContext();
        var service = new ExameService(new ExameRepository(ctx));
        await service.SolicitarAsync(new Exame { PacienteId = 1, MedicoId = 1, Tipo = "A", Laboratorio = "L" });
        await service.SolicitarAsync(new Exame { PacienteId = 1, MedicoId = 1, Tipo = "B", Laboratorio = "L" });

        var result = await service.GetByPacienteAsync(1);

        Assert.Equal(2, result.Count());
    }

    [Fact]
    public async Task GetByStatus_DeveRetornarFiltradoPorStatus()
    {
        var ctx = CreateContext();
        var service = new ExameService(new ExameRepository(ctx));
        await service.SolicitarAsync(new Exame { PacienteId = 1, MedicoId = 1, Tipo = "A", Laboratorio = "L" });

        var pendentes = await service.GetByStatusAsync(StatusExame.Pendente);
        var agendados = await service.GetByStatusAsync(StatusExame.Agendado);

        Assert.Single(pendentes);
        Assert.Empty(agendados);
    }
}
