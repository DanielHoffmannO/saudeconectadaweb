<!-- Badges no topo -->
![Version](https://img.shields.io/badge/version-1.0.0-blue)
![.NET](https://img.shields.io/badge/.NET-9.0-purple)
![Docker](https://img.shields.io/badge/Docker-Ready-2496ED)
![License](https://img.shields.io/badge/license-MIT-green)
![Build](https://img.shields.io/badge/build-passing-brightgreen)
![Tests](https://img.shields.io/badge/tests-95%25-success)

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

## 💡 Sobre o Projeto

**Saúde Conectada** é uma plataforma moderna de telemedicina que facilita o agendamento 
de consultas online entre pacientes e médicos. O sistema oferece:

- ✅ Cadastro e gestão de pacientes
- ✅ Agenda de médicos com horários disponíveis
- ✅ Agendamento e cancelamento de consultas
- ✅ Autenticação segura com JWT
- ✅ Interface responsiva e amigável
- ✅ Dashboard administrativo

## 📋 Pré-requisitos

- Docker 20.10+ ou .NET 9 SDK
- Git

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
