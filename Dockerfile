FROM mcr.microsoft.com/dotnet/sdk:9.0 AS build
WORKDIR /src

COPY SaudeConectada.sln .
COPY src/SaudeConectada.Api/SaudeConectada.Api.csproj src/SaudeConectada.Api/
COPY src/SaudeConectada.Service/SaudeConectada.Service.csproj src/SaudeConectada.Service/
COPY src/SaudeConectada.Domain/SaudeConectada.Domain.csproj src/SaudeConectada.Domain/
COPY src/SaudeConectada.Persistence/SaudeConectada.Persistence.csproj src/SaudeConectada.Persistence/
RUN dotnet restore

COPY . .
RUN dotnet publish src/SaudeConectada.Api -c Release -o /app/publish

FROM mcr.microsoft.com/dotnet/aspnet:9.0 AS runtime
WORKDIR /app
COPY --from=build /app/publish .

ENV ASPNETCORE_URLS=http://+:5000
EXPOSE 5000

ENTRYPOINT ["dotnet", "SaudeConectada.Api.dll"]
