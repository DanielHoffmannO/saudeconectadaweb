🌐 [Português](README.md) | [English](README.en.md)

![Version](https://img.shields.io/badge/version-1.0.0-blue)
![.NET](https://img.shields.io/badge/.NET-9.0-purple)
![Docker](https://img.shields.io/badge/Docker-Ready-2496ED)
![License](https://img.shields.io/badge/license-MIT-green)
![Build](https://img.shields.io/badge/build-passing-brightgreen)
![Tests](https://img.shields.io/badge/tests-95%25-success)

## 📄 Licencia

Este proyecto está bajo la licencia MIT. Vea el archivo [LICENSE](LICENSE) para más detalles.

## 💡 Sobre el Proyecto

**Saúde Conectada** es una plataforma moderna de telemedicina que facilita la programación de consultas en línea entre pacientes y médicos. El sistema ofrece:

- ✅ Registro y gestión de pacientes
- ✅ Agenda de médicos con horarios disponibles
- ✅ Programación y cancelación de consultas
- ✅ Autenticación segura con JWT
- ✅ Interfaz responsiva y amigable
- ✅ Dashboard administrativo

## 📋 Prerrequisitos

- Docker 20.10+ o .NET 9 SDK
- Git

## Tech Stack

- .NET 9 / ASP.NET Core Web API
- Entity Framework Core + SQLite
- JWT Authentication
- Vanilla JS (SPA)
- Docker

## Cómo Ejecutar

```bash
docker-compose up --build
```

- **Front-end:** http://localhost:8080
- **API (Swagger):** http://localhost:5000/swagger

## Sin Docker

```bash
dotnet run --project src/SaudeConectada.Api
```

## Tests

```bash
dotnet test
```

## Datos de prueba (seed automático)

| Tipo | Email | Contraseña |
|------|-------|------------|
| Admin | admin@saude.com | admin123 |

Médicos y pacientes se crean automáticamente en el primer inicio.

## Arquitectura

```
src/
├── SaudeConectada.Domain        ← Entidades, DTOs, Interfaces (DDD)
├── SaudeConectada.Service       ← Reglas de negocio
├── SaudeConectada.Persistence   ← EF Core, Repositories
└── SaudeConectada.Api           ← Controllers, JWT, Swagger
tests/
└── SaudeConectada.Tests         ← xUnit
```

## Autor

Daniel Hoffmann
