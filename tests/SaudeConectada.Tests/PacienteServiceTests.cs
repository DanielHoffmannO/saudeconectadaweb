using Microsoft.EntityFrameworkCore;
using SaudeConectada.Domain.Entities;
using SaudeConectada.Persistence.Data;
using SaudeConectada.Persistence.Repositories;
using SaudeConectada.Service.Services;

namespace SaudeConectada.Tests;

public class PacienteServiceTests
{
    private AppDbContext CreateContext()
    {
        var options = new DbContextOptionsBuilder<AppDbContext>()
            .UseInMemoryDatabase(Guid.NewGuid().ToString())
            .Options;
        return new AppDbContext(options);
    }

    [Fact]
    public async Task Create_DeveAdicionarPaciente()
    {
        var ctx = CreateContext();
        var service = new PacienteService(new PacienteRepository(ctx));

        var paciente = new Paciente { Nome = "Maria", CPF = "123.456.789-00", Email = "maria@test.com", Telefone = "11999", DataNascimento = new DateTime(1985, 5, 10) };
        var result = await service.CreateAsync(paciente);

        Assert.True(result.Id > 0);
        Assert.Equal("Maria", result.Nome);
    }

    [Fact]
    public async Task GetById_DeveRetornarPaciente()
    {
        var ctx = CreateContext();
        var service = new PacienteService(new PacienteRepository(ctx));
        var paciente = await service.CreateAsync(new Paciente { Nome = "João", CPF = "111.222.333-44", Email = "joao@test.com", Telefone = "11888", DataNascimento = new DateTime(1990, 1, 1) });

        var result = await service.GetByIdAsync(paciente.Id);

        Assert.NotNull(result);
        Assert.Equal("João", result!.Nome);
    }

    [Fact]
    public async Task GetById_Inexistente_RetornaNull()
    {
        var ctx = CreateContext();
        var service = new PacienteService(new PacienteRepository(ctx));

        var result = await service.GetByIdAsync(999);

        Assert.Null(result);
    }

    [Fact]
    public async Task GetByCpf_DeveRetornarPaciente()
    {
        var ctx = CreateContext();
        var service = new PacienteService(new PacienteRepository(ctx));
        await service.CreateAsync(new Paciente { Nome = "Ana", CPF = "999.888.777-66", Email = "ana@test.com", Telefone = "11777", DataNascimento = new DateTime(1995, 3, 20) });

        var result = await service.GetByCpfAsync("999.888.777-66");

        Assert.NotNull(result);
        Assert.Equal("Ana", result!.Nome);
    }

    [Fact]
    public async Task GetAll_DeveRetornarTodos()
    {
        var ctx = CreateContext();
        var service = new PacienteService(new PacienteRepository(ctx));
        await service.CreateAsync(new Paciente { Nome = "A", CPF = "1", Email = "a@t.com", Telefone = "1", DataNascimento = DateTime.Today });
        await service.CreateAsync(new Paciente { Nome = "B", CPF = "2", Email = "b@t.com", Telefone = "2", DataNascimento = DateTime.Today });

        var result = await service.GetAllAsync();

        Assert.Equal(2, result.Count());
    }

    [Fact]
    public async Task Delete_DeveRemoverPaciente()
    {
        var ctx = CreateContext();
        var service = new PacienteService(new PacienteRepository(ctx));
        var paciente = await service.CreateAsync(new Paciente { Nome = "X", CPF = "3", Email = "x@t.com", Telefone = "3", DataNascimento = DateTime.Today });

        await service.DeleteAsync(paciente.Id);

        Assert.Null(await service.GetByIdAsync(paciente.Id));
    }

    [Fact]
    public async Task Update_DeveAlterarDados()
    {
        var ctx = CreateContext();
        var service = new PacienteService(new PacienteRepository(ctx));
        var paciente = await service.CreateAsync(new Paciente { Nome = "Antes", CPF = "4", Email = "old@t.com", Telefone = "4", DataNascimento = DateTime.Today });

        paciente.Nome = "Depois";
        paciente.Email = "new@t.com";
        await service.UpdateAsync(paciente);

        var atualizado = await service.GetByIdAsync(paciente.Id);
        Assert.Equal("Depois", atualizado!.Nome);
        Assert.Equal("new@t.com", atualizado.Email);
    }
}
