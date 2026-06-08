using Microsoft.EntityFrameworkCore;
using Microsoft.EntityFrameworkCore.Metadata.Builders;
using SaudeConectada.Domain.Entities;

namespace SaudeConectada.Persistence.Configurations;

public class MedicoConfiguration : IEntityTypeConfiguration<Medico>
{
    public void Configure(EntityTypeBuilder<Medico> builder)
    {
        builder.HasKey(m => m.Id);
        builder.Property(m => m.Nome).IsRequired().HasMaxLength(200);
        builder.Property(m => m.CRM).IsRequired().HasMaxLength(20);
        builder.Property(m => m.Email).IsRequired().HasMaxLength(200);
        builder.Property(m => m.Telefone).HasMaxLength(20);
        builder.Property(m => m.Especialidade).IsRequired();
    }
}
