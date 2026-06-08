using SaudeConectada.Domain.Entities;
using SaudeConectada.Domain.Enums;
using SaudeConectada.Persistence.Data;
using System.Security.Cryptography;
using System.Text;

namespace SaudeConectada.Api;

public static class DbSeed
{
    public static void Run(AppDbContext db)
    {
        if (db.Usuarios.Any()) return;

        db.Usuarios.Add(new Usuario { Nome = "Admin", Email = "admin@saude.com", SenhaHash = Hash("admin123"), Role = "admin", CriadoEm = DateTime.UtcNow });

        db.Medicos.AddRange(
            new Medico { Nome = "Dra. Ana Lima", CRM = "12345-SP", Especialidade = Especialidade.Cardiologia, Email = "ana@saude.com", Telefone = "(11) 99999-0001" },
            new Medico { Nome = "Dr. Carlos Souza", CRM = "67890-RJ", Especialidade = Especialidade.Neurologia, Email = "carlos@saude.com", Telefone = "(21) 99999-0002" },
            new Medico { Nome = "Dra. Julia Martins", CRM = "11111-MG", Especialidade = Especialidade.Pediatria, Email = "julia@saude.com", Telefone = "(31) 99999-0003" }
        );

        db.Pacientes.AddRange(
            new Paciente { Nome = "João Silva", CPF = "111.222.333-44", Email = "joao@email.com", Telefone = "(11) 98888-0001", DataNascimento = new DateTime(1990, 5, 15) },
            new Paciente { Nome = "Maria Oliveira", CPF = "555.666.777-88", Email = "maria@email.com", Telefone = "(11) 98888-0002", DataNascimento = new DateTime(1985, 8, 22) }
        );

        db.SaveChanges();
    }

    private static string Hash(string s) => Convert.ToBase64String(SHA256.HashData(Encoding.UTF8.GetBytes(s)));
}
