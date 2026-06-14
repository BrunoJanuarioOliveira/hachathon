package br.unialfa.backoffice.gui;

import br.unialfa.backoffice.util.ConexaoBD;

import javax.swing.*;
import java.awt.*;

public class MainFrame extends JFrame {

    public MainFrame() {
        setTitle("Back Office — UniALFA");
        setSize(1000, 650);
        setDefaultCloseOperation(EXIT_ON_CLOSE);
        setLocationRelativeTo(null);

        // Barra de menu
        JMenuBar menuBar = new JMenuBar();

        JMenu menuAlunos = new JMenu("Alunos");
        JMenuItem miAlunos = new JMenuItem("Gerenciar Alunos");
        menuAlunos.add(miAlunos);

        JMenu menuEmpresas = new JMenu("Empresas");
        JMenuItem miEmpresas = new JMenuItem("Gerenciar Empresas");
        menuEmpresas.add(miEmpresas);

        JMenu menuRelat = new JMenu("Relatórios");
        JMenuItem miRelatAlunos    = new JMenuItem("Exportar Alunos (.txt)");
        JMenuItem miRelatEmpresas  = new JMenuItem("Exportar Empresas (.txt)");
        menuRelat.add(miRelatAlunos);
        menuRelat.add(miRelatEmpresas);

        menuBar.add(menuAlunos);
        menuBar.add(menuEmpresas);
        menuBar.add(menuRelat);
        setJMenuBar(menuBar);

        // Painel central (troca conforme item de menu clicado)
        setContentPane(new AlunoPanel()); // painel inicial

        // Listeners de navegação
        miAlunos.addActionListener(e -> trocarPainel(new AlunoPanel()));
        miEmpresas.addActionListener(e -> trocarPainel(new EmpresaPanel()));

        // Atalhos de relatório direto pelo menu (abre o painel correspondente)
        miRelatAlunos.addActionListener(e -> {
            AlunoPanel p = new AlunoPanel();
            trocarPainel(p);
        });
        miRelatEmpresas.addActionListener(e -> {
            EmpresaPanel p = new EmpresaPanel();
            trocarPainel(p);
        });

        setVisible(true);
    }

    private void trocarPainel(JPanel painel) {
        setContentPane(painel);
        revalidate();
        repaint();
    }

    public static void main(String[] args) {
        // Configura look and feel do sistema operacional
        try {
            UIManager.setLookAndFeel(UIManager.getSystemLookAndFeelClassName());
        } catch (Exception ignored) {}

        // Testa conexão antes de abrir a janela
        if (!ConexaoBD.testarConexao()) {
            JOptionPane.showMessageDialog(null,
                "Não foi possível conectar ao MySQL.\n" +
                "Verifique se o servidor está rodando e se a senha em ConexaoBD.java está correta.",
                "Erro de Conexão", JOptionPane.ERROR_MESSAGE);
            System.exit(1);
        }

        SwingUtilities.invokeLater(MainFrame::new);
    }
}
