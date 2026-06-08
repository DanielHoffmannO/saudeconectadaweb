using Microsoft.EntityFrameworkCore;
using Microsoft.EntityFrameworkCore.Metadata.Builders;
using SaudeConectada.Domain.Entities;

namespace SaudeConectada.Persistence.Configurations;

public class PacienteConfiguration : IEntityTypeConfiguration<Paciente>
{
    public void Configure(EntityTypeBuilder<Paciente> builder)
    {
        builder.HasKey(p => p.Id);
        builder.Property(p => p.Nome).IsRequired().HasMaxLength(200);
        builder.Property(p => p.CPF).IsRequired().HasMaxLength(14);
        builder.Property(p => p.Email).IsRequired().HasMaxLength(200);
        builder.Property(p => p.Telefone).HasMaxLength(20);
        builder.Property(p => p.DataNascimento).IsRequired();
    }
}
