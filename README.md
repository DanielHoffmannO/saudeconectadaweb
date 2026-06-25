🌐 [English](README.en.md) | [Español](README.es.md)

# 🏥 Saúde Conectada

[![.NET CI](https://github.com/DanielHoffmannO/SaudeConectada/actions/workflows/dotnet.yml/badge.svg)](https://github.com/DanielHoffmannO/SaudeConectada/actions/workflows/dotnet.yml)
[![.NET 9](https://img.shields.io/badge/.NET-9.0-512BD4?logo=dotnet&logoColor=white)](https://dotnet.microsoft.com/)
[![SQLite](https://img.shields.io/badge/SQLite-003B57?logo=sqlite&logoColor=white)](https://www.sqlite.org/)
[![Docker](https://img.shields.io/badge/Docker-2496ED?logo=docker&logoColor=white)](https://www.docker.com/)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)

> Plataforma de telemedicina completa com agendamento de consultas, gestão de pacientes e médicos, exames, notificações e mensagens em tempo real.

## 🛠️ Tech Stack

| Camada | Tecnologia |
|--------|-----------|
| Backend | .NET 9 / ASP.NET Core Web API |
| ORM | Entity Framework Core |
| Banco de Dados | SQLite |
| Autenticação | JWT Bearer Token |
| Frontend | Vanilla JS (SPA) |
| Testes | xUnit |
| CI/CD | GitHub Actions |
| Container | Docker Compose |

## 🚀 Como Rodar

### Com Docker (recomendado)

```bash
git clone https://github.com/DanielHoffmannO/SaudeConectada.git
cd SaudeConectada
docker-compose up --build
```

- 🖥️ Frontend: http://localhost:8080
- 📡 API/Swagger: http://localhost:5000/swagger

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

### 🔑 Credenciais de Teste

| Email | Senha |
|-------|-------|
| admin@saude.com | admin123 |

> O seed automático cria médicos e pacientes de exemplo ao iniciar a aplicação.

## ✨ Features

- 📅 Agendamento de consultas online
- 👨‍⚕️ Cadastro e gestão de médicos
- 🧑‍🤝‍🧑 Cadastro e gestão de pacientes
- 🔬 Gestão de exames
- 💬 Sistema de mensagens
- 🔔 Notificações
- 🔐 Autenticação JWT
- 🌱 Seed automático de dados
- 🐳 Deploy com Docker Compose
- ✅ Testes automatizados
- 🔄 CI com GitHub Actions

## 🏗️ Arquitetura

Clean Architecture com separação em camadas:

```
SaudeConectada/
├── src/
│   ├── SaudeConectada.Api/           # Controllers, Middlewares, Config
│   ├── SaudeConectada.Domain/        # Entidades, Interfaces, Enums
│   ├── SaudeConectada.Service/       # Regras de negócio, DTOs
│   └── SaudeConectada.Persistence/   # DbContext, Repositórios, Migrations
├── tests/
│   └── SaudeConectada.Tests/         # Testes unitários (xUnit)
├── frontend/                          # SPA Vanilla JS
├── docker-compose.yml
└── README.md
```

## 🧪 Testes

```bash
dotnet test
```

Cobertura de testes unitários com xUnit:

- `AuthService`
- `PacienteService`
- `MedicoService`
- `ConsultaService`
- `ExameService`
- `NotificacaoService`
- `MensagemService`

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

## 👤 Autor

**Daniel Hoffmann**

[![LinkedIn](https://img.shields.io/badge/LinkedIn-0A66C2?logo=linkedin&logoColor=white)](https://www.linkedin.com/in/danielhoffmanno/)
[![GitHub](https://img.shields.io/badge/GitHub-181717?logo=github&logoColor=white)](https://github.com/DanielHoffmannO)
