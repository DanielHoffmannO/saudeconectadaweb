using Microsoft.EntityFrameworkCore;
using Microsoft.EntityFrameworkCore.Metadata.Builders;
using SaudeConectada.Domain.Entities;

namespace SaudeConectada.Persistence.Configurations;

public class MensagemConfiguration : IEntityTypeConfiguration<Mensagem>
{
    public void Configure(EntityTypeBuilder<Mensagem> builder)
    {
        builder.HasKey(m => m.Id);
        builder.Property(m => m.Conteudo).IsRequired().HasMaxLength(2000);
        builder.Property(m => m.EnviadaEm).IsRequired();

        builder.HasOne(m => m.Remetente).WithMany().HasForeignKey(m => m.RemetenteId).OnDelete(DeleteBehavior.Restrict);
        builder.HasOne(m => m.Destinatario).WithMany().HasForeignKey(m => m.DestinatarioId).OnDelete(DeleteBehavior.Restrict);
    }
}
