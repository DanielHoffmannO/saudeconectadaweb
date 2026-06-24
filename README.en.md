🌐 [Português](README.md) | [Español](README.es.md)

![Version](https://img.shields.io/badge/version-1.0.0-blue)
![.NET](https://img.shields.io/badge/.NET-9.0-purple)
![Docker](https://img.shields.io/badge/Docker-Ready-2496ED)
![License](https://img.shields.io/badge/license-MIT-green)
![Build](https://img.shields.io/badge/build-passing-brightgreen)
![Tests](https://img.shields.io/badge/tests-95%25-success)

## 📄 License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## 💡 About the Project

**Saúde Conectada** is a modern telemedicine platform that facilitates online appointment scheduling between patients and doctors. The system offers:

- ✅ Patient registration and management
- ✅ Doctor schedules with available time slots
- ✅ Appointment scheduling and cancellation
- ✅ Secure JWT authentication
- ✅ Responsive and user-friendly interface
- ✅ Administrative dashboard

## 📋 Prerequisites

- Docker 20.10+ or .NET 9 SDK
- Git

## Tech Stack

- .NET 9 / ASP.NET Core Web API
- Entity Framework Core + SQLite
- JWT Authentication
- Vanilla JS (SPA)
- Docker

## How to Run

```bash
docker-compose up --build
```

- **Front-end:** http://localhost:8080
- **API (Swagger):** http://localhost:5000/swagger

## Without Docker

```bash
dotnet run --project src/SaudeConectada.Api
```

## Tests

```bash
dotnet test
```

## Test Data (automatic seed)

| Type | Email | Password |
|------|-------|----------|
| Admin | admin@saude.com | admin123 |

Doctors and patients are created automatically on first start.

## Architecture

```
src/
├── SaudeConectada.Domain        ← Entities, DTOs, Interfaces (DDD)
├── SaudeConectada.Service       ← Business rules
├── SaudeConectada.Persistence   ← EF Core, Repositories
└── SaudeConectada.Api           ← Controllers, JWT, Swagger
tests/
└── SaudeConectada.Tests         ← xUnit
```

## Author

Daniel Hoffmann
