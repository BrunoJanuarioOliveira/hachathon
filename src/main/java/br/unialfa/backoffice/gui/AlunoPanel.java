package br.unialfa.backoffice.gui;

import br.unialfa.backoffice.dao.AlunoDAO;
import br.unialfa.backoffice.model.Aluno;
import br.unialfa.backoffice.util.RelatorioUtil;

import javax.swing.*;
import javax.swing.table.DefaultTableModel;
import java.awt.*;
import java.io.File;
import java.util.List;

public class AlunoPanel extends JPanel {

    private final AlunoDAO dao = new AlunoDAO();
    private final DefaultTableModel modelo = new DefaultTableModel(
        new String[]{"ID", "Nome", "RA", "Email", "Status"}, 0) {
        @Override public boolean isCellEditable(int r, int c) { return false; }
    };
    private final JTable tabela = new JTable(modelo);

    public AlunoPanel() {
        setLayout(new BorderLayout(8, 8));
        setBorder(BorderFactory.createEmptyBorder(12, 12, 12, 12));

        // Tabela
        tabela.setSelectionMode(ListSelectionModel.SINGLE_SELECTION);
        tabela.setRowHeight(24);
        tabela.getColumnModel().getColumn(0).setMaxWidth(50);
        tabela.getColumnModel().getColumn(4).setPreferredWidth(90);
        add(new JScrollPane(tabela), BorderLayout.CENTER);

        // Barra de botões
        JPanel bar = new JPanel(new FlowLayout(FlowLayout.LEFT, 6, 0));
        JButton btnAdd      = new JButton("+ Adicionar");
        JButton btnBloquear = new JButton("Bloquear / Desbloquear");
        JButton btnRelat    = new JButton("Exportar .txt");
        JButton btnAtualizar = new JButton("↻ Atualizar");
        bar.add(btnAdd);
        bar.add(btnBloquear);
        bar.add(btnRelat);
        bar.add(btnAtualizar);
        add(bar, BorderLayout.SOUTH);

        // Ações
        btnAtualizar.addActionListener(e -> carregarDados());

        btnAdd.addActionListener(e -> {
            JTextField nome  = new JTextField();
            JTextField ra    = new JTextField();
            JTextField email = new JTextField();
            JCheckBox  apto  = new JCheckBox("Apto para estágio", true);
            Object[] campos = {"Nome:", nome, "RA:", ra, "E-mail:", email, apto};
            int res = JOptionPane.showConfirmDialog(this, campos, "Novo Aluno", JOptionPane.OK_CANCEL_OPTION);
            if (res != JOptionPane.OK_OPTION) return;
            if (nome.getText().isBlank() || ra.getText().isBlank()) {
                JOptionPane.showMessageDialog(this, "Nome e RA são obrigatórios.", "Erro", JOptionPane.ERROR_MESSAGE);
                return;
            }
            try {
                dao.salvar(new Aluno(0, nome.getText().trim(), ra.getText().trim(),
                                     email.getText().trim(), apto.isSelected()));
                carregarDados();
            } catch (Exception ex) {
                JOptionPane.showMessageDialog(this, "Erro ao salvar: " + ex.getMessage(), "Erro", JOptionPane.ERROR_MESSAGE);
            }
        });

        btnBloquear.addActionListener(e -> {
            int row = tabela.getSelectedRow();
            if (row < 0) { JOptionPane.showMessageDialog(this, "Selecione um aluno."); return; }
            int id      = (int) modelo.getValueAt(row, 0);
            String stat = (String) modelo.getValueAt(row, 4);
            boolean novoApto = stat.equals("BLOQUEADO");
            String acao = novoApto ? "desbloquear" : "bloquear";
            int conf = JOptionPane.showConfirmDialog(this, "Deseja " + acao + " este aluno?",
                "Confirmar", JOptionPane.YES_NO_OPTION);
            if (conf != JOptionPane.YES_OPTION) return;
            try {
                dao.alterarApto(id, novoApto);
                carregarDados();
            } catch (Exception ex) {
                JOptionPane.showMessageDialog(this, "Erro: " + ex.getMessage(), "Erro", JOptionPane.ERROR_MESSAGE);
            }
        });

        btnRelat.addActionListener(e -> {
            JFileChooser fc = new JFileChooser();
            fc.setSelectedFile(new File("relatorio_alunos.txt"));
            if (fc.showSaveDialog(this) != JFileChooser.APPROVE_OPTION) return;
            try {
                List<Aluno> lista = dao.listarTodos();
                RelatorioUtil.gerarRelatorioAlunos(lista, fc.getSelectedFile().getAbsolutePath());
                JOptionPane.showMessageDialog(this, "Relatório exportado com sucesso!");
            } catch (Exception ex) {
                JOptionPane.showMessageDialog(this, "Erro: " + ex.getMessage(), "Erro", JOptionPane.ERROR_MESSAGE);
            }
        });

        carregarDados();
    }

    public void carregarDados() {
        modelo.setRowCount(0);
        try {
            for (Aluno a : dao.listarTodos()) {
                modelo.addRow(new Object[]{
                    a.getId(), a.getNome(), a.getRa(), a.getEmail(),
                    a.isApto() ? "APTO" : "BLOQUEADO"
                });
            }
        } catch (Exception ex) {
            JOptionPane.showMessageDialog(this, "Erro ao carregar alunos: " + ex.getMessage(),
                "Erro", JOptionPane.ERROR_MESSAGE);
        }
    }
}
