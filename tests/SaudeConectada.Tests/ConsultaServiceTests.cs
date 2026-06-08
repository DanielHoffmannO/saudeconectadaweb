using Microsoft.EntityFrameworkCore;
using SaudeConectada.Domain.Entities;
using SaudeConectada.Domain.Enums;
using SaudeConectada.Persistence.Data;
using SaudeConectada.Persistence.Repositories;
using SaudeConectada.Service.Services;

namespace SaudeConectada.Tests;

public class ConsultaServiceTests
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
    public async Task Agendar_DeveRetornarConsulta_QuandoHorarioLivre()
    {
        var ctx = CreateContext();
        var service = new ConsultaService(new ConsultaRepository(ctx), new MedicoRepository(ctx), new PacienteRepository(ctx));

        var consulta = new Consulta { MedicoId = 1, PacienteId = 1, DataHora = DateTime.Today.AddHours(10) };
        var result = await service.AgendarAsync(consulta);

        Assert.Equal(StatusConsulta.Agendada, result.Status);
        Assert.Equal(1, result.MedicoId);
    }

    [Fact]
    public async Task Agendar_DeveLancarExcecao_QuandoConflitoHorario()
    {
        var ctx = CreateContext();
        var service = new ConsultaService(new ConsultaRepository(ctx), new MedicoRepository(ctx), new PacienteRepository(ctx));
        var horario = DateTime.Today.AddHours(14);

        await service.AgendarAsync(new Consulta { MedicoId = 1, PacienteId = 1, DataHora = horario });

        await Assert.ThrowsAsync<InvalidOperationException>(() =>
            service.AgendarAsync(new Consulta { MedicoId = 1, PacienteId = 1, DataHora = horario }));
    }

    [Fact]
    public async Task Cancelar_DeveAlterarStatus()
    {
        var ctx = CreateContext();
        var service = new ConsultaService(new ConsultaRepository(ctx), new MedicoRepository(ctx), new PacienteRepository(ctx));

        var consulta = await service.AgendarAsync(new Consulta { MedicoId = 1, PacienteId = 1, DataHora = DateTime.Today.AddHours(16) });
        await service.CancelarAsync(consulta.Id);

        var cancelada = await ctx.Consultas.FindAsync(consulta.Id);
        Assert.Equal(StatusConsulta.Cancelada, cancelada!.Status);
    }
}
