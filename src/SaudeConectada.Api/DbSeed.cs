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

        var admin = new Usuario { Nome = "Admin", Email = "admin@saude.com", SenhaHash = Hash("admin123"), Role = "admin", CriadoEm = DateTime.UtcNow };
        db.Usuarios.Add(admin);

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

        // Seed 20 exames
        var tiposExame = new[] { "Hemograma completo", "Glicemia em jejum", "Colesterol total", "Triglicerídeos", "TSH", "T4 livre", "Eletrocardiograma", "Raio-X Tórax", "Urina tipo I", "Creatinina", "Ureia", "TGO/TGP", "Ácido úrico", "PSA", "Vitamina D", "Ferro sérico", "Ferritina", "PCR", "VHS", "Hemoglobina glicada" };
        var laboratorios = new[] { "Lab São Lucas", "Fleury", "Delboni", "Lavoisier" };
        var statusList = new[] { StatusExame.Disponivel, StatusExame.Agendado, StatusExame.Pendente };

        for (int i = 0; i < 20; i++)
        {
            db.Exames.Add(new Exame
            {
                PacienteId = (i % 2) + 1,
                MedicoId = (i % 3) + 1,
                Tipo = tiposExame[i],
                Laboratorio = laboratorios[i % 4],
                DataSolicitacao = DateTime.UtcNow.AddDays(-(20 - i)),
                DataRealizacao = statusList[i % 3] == StatusExame.Disponivel ? DateTime.UtcNow.AddDays(-(10 - i)) : null,
                Status = statusList[i % 3],
                Resultado = statusList[i % 3] == StatusExame.Disponivel ? "Dentro dos parâmetros normais" : null,
                Observacoes = i % 5 == 0 ? "Jejum de 12h" : null
            });
        }

        // Seed notificações
        db.Notificacoes.AddRange(
            new Notificacao { UsuarioId = 1, Tipo = TipoNotificacao.Consulta, Titulo = "Consulta agendada", Mensagem = "Sua consulta com Dra. Ana foi marcada para amanhã às 14:30", Lida = false, CriadaEm = DateTime.UtcNow.AddMinutes(-5) },
            new Notificacao { UsuarioId = 1, Tipo = TipoNotificacao.Exame, Titulo = "Resultado disponível", Mensagem = "Seu hemograma completo já está disponível no portal", Lida = false, CriadaEm = DateTime.UtcNow.AddHours(-1) },
            new Notificacao { UsuarioId = 1, Tipo = TipoNotificacao.Mensagem, Titulo = "Nova mensagem", Mensagem = "Dr. Carlos enviou uma mensagem sobre seu tratamento", Lida = true, CriadaEm = DateTime.UtcNow.AddHours(-3) },
            new Notificacao { UsuarioId = 1, Tipo = TipoNotificacao.Exame, Titulo = "Exame agendado", Mensagem = "Seu exame de glicemia foi agendado para 15/06 às 07:00", Lida = false, CriadaEm = DateTime.UtcNow.AddHours(-5) },
            new Notificacao { UsuarioId = 1, Tipo = TipoNotificacao.Consulta, Titulo = "Lembrete de consulta", Mensagem = "Não esqueça: consulta com Dra. Julia amanhã às 10:00", Lida = true, CriadaEm = DateTime.UtcNow.AddDays(-1) },
            new Notificacao { UsuarioId = 1, Tipo = TipoNotificacao.Exame, Titulo = "Colesterol disponível", Mensagem = "Resultado do exame de colesterol total está pronto", Lida = false, CriadaEm = DateTime.UtcNow.AddDays(-2) },
            new Notificacao { UsuarioId = 1, Tipo = TipoNotificacao.Mensagem, Titulo = "Resposta da Dra. Ana", Mensagem = "Dra. Ana respondeu sua dúvida sobre medicação", Lida = true, CriadaEm = DateTime.UtcNow.AddDays(-3) },
            new Notificacao { UsuarioId = 1, Tipo = TipoNotificacao.Consulta, Titulo = "Consulta cancelada", Mensagem = "Sua consulta de 10/06 foi cancelada pelo médico", Lida = true, CriadaEm = DateTime.UtcNow.AddDays(-4) }
        );

        db.SaveChanges();
    }

    private static string Hash(string s) => Convert.ToBase64String(SHA256.HashData(Encoding.UTF8.GetBytes(s)));
}
