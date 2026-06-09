using Microsoft.EntityFrameworkCore;
using Microsoft.EntityFrameworkCore.Metadata.Builders;
using SaudeConectada.Domain.Entities;

namespace SaudeConectada.Persistence.Configurations;

public class ExameConfiguration : IEntityTypeConfiguration<Exame>
{
    public void Configure(EntityTypeBuilder<Exame> builder)
    {
        builder.HasKey(e => e.Id);
        builder.Property(e => e.Tipo).IsRequired().HasMaxLength(200);
        builder.Property(e => e.Laboratorio).IsRequired().HasMaxLength(200);
        builder.Property(e => e.Status).IsRequired();
        builder.Property(e => e.Resultado).HasMaxLength(1000);
        builder.Property(e => e.Observacoes).HasMaxLength(1000);

        builder.HasOne(e => e.Paciente).WithMany().HasForeignKey(e => e.PacienteId).OnDelete(DeleteBehavior.Restrict);
        builder.HasOne(e => e.Medico).WithMany().HasForeignKey(e => e.MedicoId).OnDelete(DeleteBehavior.Restrict);
    }
}
