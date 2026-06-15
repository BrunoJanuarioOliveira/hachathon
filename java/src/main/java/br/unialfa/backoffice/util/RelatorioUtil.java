package br.unialfa.backoffice.util;

import br.unialfa.backoffice.model.Aluno;
import br.unialfa.backoffice.model.Empresa;
import br.unialfa.backoffice.model.Vaga;

import java.io.FileWriter;
import java.io.IOException;
import java.io.PrintWriter;
import java.time.LocalDateTime;
import java.time.format.DateTimeFormatter;
import java.util.List;

public class RelatorioUtil {

    private static final DateTimeFormatter FMT = DateTimeFormatter.ofPattern("dd/MM/yyyy HH:mm:ss");

    public static void gerarRelatorioAlunos(List<Aluno> lista, String caminho) throws IOException {
        try (PrintWriter pw = new PrintWriter(new FileWriter(caminho))) {
            pw.println("=".repeat(65));
            pw.println("  RELATÓRIO DE ALUNOS — UniALFA");
            pw.println("  Gerado em: " + LocalDateTime.now().format(FMT));
            pw.println("=".repeat(65));
            pw.printf("%-5s %-30s %-12s %-25s %s%n", "ID", "Nome", "RA", "Email", "Status");
            pw.println("-".repeat(65));
            for (Aluno a : lista) {
                pw.printf("%-5d %-30s %-12s %-25s %s%n",
                    a.getId(), a.getNome(), a.getRa(), a.getEmail(),
                    a.isApto() ? "APTO" : "BLOQUEADO");
            }
            pw.println("-".repeat(65));
            pw.println("Total: " + lista.size() + " aluno(s)");
        }
    }

    public static void gerarRelatorioEmpresas(List<Empresa> lista, String caminho) throws IOException {
        try (PrintWriter pw = new PrintWriter(new FileWriter(caminho))) {
            pw.println("=".repeat(65));
            pw.println("  RELATÓRIO DE EMPRESAS — UniALFA");
            pw.println("  Gerado em: " + LocalDateTime.now().format(FMT));
            pw.println("=".repeat(65));
            pw.printf("%-5s %-30s %-18s %s%n", "ID", "Nome", "CNPJ", "Status");
            pw.println("-".repeat(65));
            for (Empresa e : lista) {
                pw.printf("%-5d %-30s %-18s %s%n",
                    e.getId(), e.getNome(), e.getCnpj(),
                    e.getStatus().toUpperCase());
            }
            pw.println("-".repeat(65));
            pw.println("Total: " + lista.size() + " empresa(s)");
        }
    }

    public static void gerarRelatorioVagas(List<Vaga> lista, String caminho) throws IOException {
        try (PrintWriter pw = new PrintWriter(new FileWriter(caminho))) {
            pw.println("=".repeat(65));
            pw.println("  RELATÓRIO DE VAGAS — UniALFA");
            pw.println("  Gerado em: " + LocalDateTime.now().format(FMT));
            pw.println("=".repeat(65));
            pw.printf("%-5s %-30s %-20s %s%n", "ID", "Título", "Área", "Status");
            pw.println("-".repeat(65));
            for (Vaga v : lista) {
                pw.printf("%-5d %-30s %-20s %s%n",
                    v.getId(), v.getTitulo(), v.getArea(),
                    v.isAtiva() ? "ATIVA" : "ENCERRADA");
            }
            pw.println("-".repeat(65));
            pw.println("Total: " + lista.size() + " vaga(s)");
        }
    }
}
