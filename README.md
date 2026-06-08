# Saúde Conectada

Sistema de telemedicina — agendamento de consultas, gestão de médicos e pacientes.

## Tech Stack

- .NET 9 / ASP.NET Core Web API
- Entity Framework Core + SQLite
- JWT Authentication
- Vanilla JS (SPA)
- Docker

## Como rodar

```bash
docker-compose up --build
```

- **Front-end:** http://localhost:8080
- **API (Swagger):** http://localhost:5000/swagger

## Sem Docker

```bash
dotnet run --project src/SaudeConectada.Api
```

## Testes

```bash
dotnet test
```

## Dados de teste (seed automático)

| Tipo | Email | Senha |
|------|-------|-------|
| Admin | admin@saude.com | admin123 |

Médicos e pacientes são criados automaticamente no primeiro start.

## Arquitetura

```
src/
├── SaudeConectada.Domain        ← Entidades, DTOs, Interfaces (DDD)
├── SaudeConectada.Service       ← Regras de negócio
├── SaudeConectada.Persistence   ← EF Core, Repositories
└── SaudeConectada.Api           ← Controllers, JWT, Swagger
tests/
└── SaudeConectada.Tests         ← xUnit
```

## Autor

Daniel Hoffmann
