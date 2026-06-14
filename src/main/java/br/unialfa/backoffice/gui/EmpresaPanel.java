package br.unialfa.backoffice.gui;

import br.unialfa.backoffice.dao.EmpresaDAO;
import br.unialfa.backoffice.model.Empresa;
import br.unialfa.backoffice.util.RelatorioUtil;

import javax.swing.*;
import javax.swing.table.DefaultTableModel;
import java.awt.*;
import java.io.File;
import java.util.List;

public class EmpresaPanel extends JPanel {

    private final EmpresaDAO dao = new EmpresaDAO();
    private final DefaultTableModel modelo = new DefaultTableModel(
        new String[]{"ID", "Nome", "CNPJ", "Email", "Telefone", "Status"}, 0) {
        @Override public boolean isCellEditable(int r, int c) { return false; }
    };
    private final JTable tabela = new JTable(modelo);

    public EmpresaPanel() {
        setLayout(new BorderLayout(8, 8));
        setBorder(BorderFactory.createEmptyBorder(12, 12, 12, 12));

        tabela.setSelectionMode(ListSelectionModel.SINGLE_SELECTION);
        tabela.setRowHeight(24);
        tabela.getColumnModel().getColumn(0).setMaxWidth(50);
        add(new JScrollPane(tabela), BorderLayout.CENTER);

        JPanel bar = new JPanel(new FlowLayout(FlowLayout.LEFT, 6, 0));
        JButton btnAdd      = new JButton("+ Adicionar");
        JButton btnAprovar  = new JButton("Aprovar / Bloquear");
        JButton btnRelat    = new JButton("Exportar .txt");
        JButton btnAtualizar = new JButton("↻ Atualizar");
        bar.add(btnAdd); bar.add(btnAprovar); bar.add(btnRelat); bar.add(btnAtualizar);
        add(bar, BorderLayout.SOUTH);

        btnAtualizar.addActionListener(e -> carregarDados());

        btnAdd.addActionListener(e -> {
            JTextField nome     = new JTextField();
            JTextField cnpj    = new JTextField();
            JTextField email   = new JTextField();
            JTextField tel     = new JTextField();
            JCheckBox  aprov   = new JCheckBox("Aprovada", true);
            Object[] campos = {"Nome:", nome, "CNPJ:", cnpj, "E-mail:", email, "Telefone:", tel, aprov};
            int res = JOptionPane.showConfirmDialog(this, campos, "Nova Empresa", JOptionPane.OK_CANCEL_OPTION);
            if (res != JOptionPane.OK_OPTION) return;
            if (nome.getText().isBlank()) {
                JOptionPane.showMessageDialog(this, "Nome é obrigatório.", "Erro", JOptionPane.ERROR_MESSAGE);
                return;
            }
            try {
                dao.salvar(new Empresa(0, nome.getText().trim(), cnpj.getText().trim(),
                    email.getText().trim(), tel.getText().trim(), aprov.isSelected()));
                carregarDados();
            } catch (Exception ex) {
                JOptionPane.showMessageDialog(this, "Erro: " + ex.getMessage(), "Erro", JOptionPane.ERROR_MESSAGE);
            }
        });

        btnAprovar.addActionListener(e -> {
            int row = tabela.getSelectedRow();
            if (row < 0) { JOptionPane.showMessageDialog(this, "Selecione uma empresa."); return; }
            int id      = (int) modelo.getValueAt(row, 0);
            String stat = (String) modelo.getValueAt(row, 5);
            boolean novoStatus = stat.equals("BLOQUEADA");
            String acao = novoStatus ? "aprovar" : "bloquear";
            int conf = JOptionPane.showConfirmDialog(this, "Deseja " + acao + " esta empresa?",
                "Confirmar", JOptionPane.YES_NO_OPTION);
            if (conf != JOptionPane.YES_OPTION) return;
            try {
                dao.alterarAprovada(id, novoStatus);
                carregarDados();
            } catch (Exception ex) {
                JOptionPane.showMessageDialog(this, "Erro: " + ex.getMessage(), "Erro", JOptionPane.ERROR_MESSAGE);
            }
        });

        btnRelat.addActionListener(e -> {
            JFileChooser fc = new JFileChooser();
            fc.setSelectedFile(new File("relatorio_empresas.txt"));
            if (fc.showSaveDialog(this) != JFileChooser.APPROVE_OPTION) return;
            try {
                List<Empresa> lista = dao.listarTodas();
                RelatorioUtil.gerarRelatorioEmpresas(lista, fc.getSelectedFile().getAbsolutePath());
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
            for (Empresa emp : dao.listarTodas()) {
                modelo.addRow(new Object[]{
                    emp.getId(), emp.getNome(), emp.getCnpj(), emp.getEmail(),
                    emp.getTelefone(), emp.isAprovada() ? "APROVADA" : "BLOQUEADA"
                });
            }
        } catch (Exception ex) {
            JOptionPane.showMessageDialog(this, "Erro ao carregar empresas: " + ex.getMessage(),
                "Erro", JOptionPane.ERROR_MESSAGE);
        }
    }
}
