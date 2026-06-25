ðŸŒ [English](README.en.md) | [EspaÃ±ol](README.es.md)

# ðŸ¥ SaÃºde Conectada

[![.NET CI](https://github.com/DanielHoffmannO/SaudeConectada/actions/workflows/dotnet.yml/badge.svg)](https://github.com/DanielHoffmannO/SaudeConectada/actions/workflows/dotnet.yml)
[![.NET 9](https://img.shields.io/badge/.NET-9.0-512BD4?logo=dotnet&logoColor=white)](https://dotnet.microsoft.com/)
[![SQLite](https://img.shields.io/badge/SQLite-003B57?logo=sqlite&logoColor=white)](https://www.sqlite.org/)
[![Docker](https://img.shields.io/badge/Docker-2496ED?logo=docker&logoColor=white)](https://www.docker.com/)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)

> Plataforma de telemedicina completa com agendamento de consultas, gestÃ£o de pacientes e mÃ©dicos, exames, notificaÃ§Ãµes e mensagens em tempo real.

## ðŸ› ï¸ Tech Stack

| Camada | Tecnologia |
|--------|-----------|
| Backend | .NET 9 / ASP.NET Core Web API |
| ORM | Entity Framework Core |
| Banco de Dados | SQLite |
| AutenticaÃ§Ã£o | JWT Bearer Token |
| Frontend | Vanilla JS (SPA) |
| Testes | xUnit |
| CI/CD | GitHub Actions |
| Container | Docker Compose |

## ðŸš€ Como Rodar

### Com Docker (recomendado)

```bash
git clone https://github.com/DanielHoffmannO/SaudeConectada.git
cd SaudeConectada
docker-compose up --build
```

- ðŸ–¥ï¸ Frontend: http://localhost:8080
- ðŸ“¡ API/Swagger: http://localhost:5000/swagger

### Sem Docker

```bash
git clone https://github.com/DanielHoffmannO/SaudeConectada.git
cd SaudeConectada

# Backend
dotnet restore
dotnet run --project src/SaudeConectada.Api

# Frontend (em outro terminal)
cd frontend
npx serve -l 8080
```

### ðŸ”‘ Credenciais de Teste

| Email | Senha |
|-------|-------|
| admin@saude.com | admin123 |

> O seed automÃ¡tico cria mÃ©dicos e pacientes de exemplo ao iniciar a aplicaÃ§Ã£o.

## âœ¨ Features

- ðŸ“… Agendamento de consultas online
- ðŸ‘¨â€âš•ï¸ Cadastro e gestÃ£o de mÃ©dicos
- ðŸ§‘â€ðŸ¤â€ðŸ§‘ Cadastro e gestÃ£o de pacientes
- ðŸ”¬ GestÃ£o de exames
- ðŸ’¬ Sistema de mensagens
- ðŸ”” NotificaÃ§Ãµes
- ðŸ” AutenticaÃ§Ã£o JWT
- ðŸŒ± Seed automÃ¡tico de dados
- ðŸ³ Deploy com Docker Compose
- âœ… Testes automatizados
- ðŸ”„ CI com GitHub Actions

## ðŸ—ï¸ Arquitetura

Clean Architecture com separaÃ§Ã£o em camadas:

```
SaudeConectada/
â”œâ”€â”€ src/
â”‚   â”œâ”€â”€ SaudeConectada.Api/           # Controllers, Middlewares, Config
â”‚   â”œâ”€â”€ SaudeConectada.Domain/        # Entidades, Interfaces, Enums
â”‚   â”œâ”€â”€ SaudeConectada.Service/       # Regras de negÃ³cio, DTOs
â”‚   â””â”€â”€ SaudeConectada.Persistence/   # DbContext, RepositÃ³rios, Migrations
â”œâ”€â”€ tests/
â”‚   â””â”€â”€ SaudeConectada.Tests/         # Testes unitÃ¡rios (xUnit)
â”œâ”€â”€ frontend/                          # SPA Vanilla JS
â”œâ”€â”€ docker-compose.yml
â””â”€â”€ README.md
```

## ðŸ§ª Testes

```bash
dotnet test
```

Cobertura de testes unitÃ¡rios com xUnit:

- `AuthService`
- `PacienteService`
- `MedicoService`
- `ConsultaService`
- `ExameService`
- `NotificacaoService`
- `MensagemService`

## ðŸ“„ LicenÃ§a

Este projeto estÃ¡ sob a licenÃ§a MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

## ðŸ‘¤ Autor

**Daniel Hoffmann**

[![LinkedIn](https://img.shields.io/badge/LinkedIn-0A66C2?logo=linkedin&logoColor=white)](https://www.linkedin.com/in/danielhoffmanno/)
[![GitHub](https://img.shields.io/badge/GitHub-181717?logo=github&logoColor=white)](https://github.com/DanielHoffmannO)
