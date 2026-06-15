package br.unialfa.backoffice.dao;

import br.unialfa.backoffice.model.Empresa;
import br.unialfa.backoffice.util.ConexaoBD;

import java.sql.*;
import java.util.ArrayList;
import java.util.List;

public class EmpresaDAO {

    public List<Empresa> listarTodas() throws SQLException {
        List<Empresa> lista = new ArrayList<>();
        String sql = "SELECT id, nome, cnpj, email, status FROM empresas ORDER BY nome";
        try (Connection con = ConexaoBD.getConnection();
             Statement st  = con.createStatement();
             ResultSet rs  = st.executeQuery(sql)) {
            while (rs.next()) {
                lista.add(new Empresa(
                    rs.getInt("id"),
                    rs.getString("nome"),
                    rs.getString("cnpj"),
                    rs.getString("email"),
                    rs.getString("status")
                ));
            }
        }
        return lista;
    }

    public void salvar(Empresa e) throws SQLException {
        String sql = "INSERT INTO empresas (nome, cnpj, email, status) VALUES (?,?,?,?)";
        try (Connection con = ConexaoBD.getConnection();
             PreparedStatement ps = con.prepareStatement(sql)) {
            ps.setString(1, e.getNome());
            ps.setString(2, e.getCnpj());
            ps.setString(3, e.getEmail());
            ps.setString(4, e.getStatus());
            ps.executeUpdate();
        }
    }

    public void alterarStatus(int id, String status) throws SQLException {
        String sql = "UPDATE empresas SET status=? WHERE id=?";
        try (Connection con = ConexaoBD.getConnection();
             PreparedStatement ps = con.prepareStatement(sql)) {
            ps.setString(1, status);
            ps.setInt(2, id);
            ps.executeUpdate();
        }
    }

    public void excluir(int id) throws SQLException {
        String sql = "DELETE FROM empresas WHERE id=?";
        try (Connection con = ConexaoBD.getConnection();
             PreparedStatement ps = con.prepareStatement(sql)) {
            ps.setInt(1, id);
            ps.executeUpdate();
        }
    }
}
