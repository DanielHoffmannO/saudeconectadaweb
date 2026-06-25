[>] [English](README.en.md) | [Espanol](README.es.md)

# {+} Saude Conectada

[![.NET CI](https://github.com/DanielHoffmannO/SaudeConectada/actions/workflows/dotnet.yml/badge.svg)](https://github.com/DanielHoffmannO/SaudeConectada/actions)
![.NET](https://img.shields.io/badge/.NET-9.0-512BD4?logo=dotnet)
![SQLite](https://img.shields.io/badge/SQLite-003B57?logo=sqlite&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-Ready-2496ED?logo=docker&logoColor=white)
![License](https://img.shields.io/badge/license-MIT-green)

> Plataforma de telemedicina -- agendamento de consultas online entre pacientes e medicos.

## {=} Tech Stack

| Camada | Tecnologia |
|--------|-----------|
| Back-end | .NET 9 / ASP.NET Core Web API |
| Banco | SQLite (EF Core) |
| Auth | JWT Bearer Token |
| Front-end | Vanilla JS (SPA) |
| Infra | Docker Compose |
| Testes | xUnit |

## [!] Como Rodar

```bash
docker-compose up --build
```

| Servico | URL |
|---------|-----|
| Front-end | http://localhost:8080 |
| API (Swagger) | http://localhost:5000/swagger |

### Sem Docker

```bash
dotnet run --project src/SaudeConectada.Api
```

### Dados de Teste (seed automatico)

| Tipo | Email | Senha |
|------|-------|-------|
| Admin | admin@saude.com | admin123 |

Medicos e pacientes sao criados automaticamente no primeiro start.

## [+] Features

- {k} Autenticacao segura com JWT
- [w] Cadastro e gestao de pacientes
- [*] Agenda de medicos com horarios disponiveis
- [>] Agendamento e cancelamento de consultas
- [~] Notificacoes e mensagens
- [x] Exames vinculados a consultas
- [<3] Interface responsiva

## {/} Arquitetura

```
src/
+-- SaudeConectada.Domain        <- Entidades, DTOs, Interfaces (DDD)
+-- SaudeConectada.Service       <- Regras de negocio
+-- SaudeConectada.Persistence   <- EF Core, Repositories
+-- SaudeConectada.Api           <- Controllers, JWT, Swagger
web/
+-- Vanilla JS SPA
tests/
+-- SaudeConectada.Tests         <- xUnit (7 test classes)
```

## [?] Testes

```bash
dotnet test
```

Cobertura: Auth, Paciente, Medico, Consulta, Exame, Notificacao, Mensagem.

## [$] Licenca

Este projeto esta sob a licenca [MIT](LICENSE).
