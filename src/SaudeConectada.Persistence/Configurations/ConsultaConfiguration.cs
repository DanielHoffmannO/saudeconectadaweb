using Microsoft.EntityFrameworkCore;
using Microsoft.EntityFrameworkCore.Metadata.Builders;
using SaudeConectada.Domain.Entities;

namespace SaudeConectada.Persistence.Configurations;

public class ConsultaConfiguration : IEntityTypeConfiguration<Consulta>
{
    public void Configure(EntityTypeBuilder<Consulta> builder)
    {
        builder.HasKey(c => c.Id);
        builder.Property(c => c.DataHora).IsRequired();
        builder.Property(c => c.Status).IsRequired();
        builder.Property(c => c.Observacoes).HasMaxLength(1000);

        builder.HasOne(c => c.Medico)
            .WithMany()
            .HasForeignKey(c => c.MedicoId)
            .OnDelete(DeleteBehavior.Restrict);

        builder.HasOne(c => c.Paciente)
            .WithMany()
            .HasForeignKey(c => c.PacienteId)
            .OnDelete(DeleteBehavior.Restrict);
    }
}
