package br.unialfa.backoffice.dao;

import br.unialfa.backoffice.model.Vaga;
import br.unialfa.backoffice.util.ConexaoBD;

import java.sql.*;
import java.util.ArrayList;
import java.util.List;

public class VagaDAO {

    public List<Vaga> listarTodas() throws SQLException {
        List<Vaga> lista = new ArrayList<>();
        String sql = "SELECT id, empresa_id, titulo, descricao, area, ativa FROM vagas ORDER BY titulo";
        try (Connection con = ConexaoBD.getConnection();
             Statement st  = con.createStatement();
             ResultSet rs  = st.executeQuery(sql)) {
            while (rs.next()) {
                lista.add(new Vaga(
                    rs.getInt("id"),
                    rs.getInt("empresa_id"),
                    rs.getString("titulo"),
                    rs.getString("descricao"),
                    rs.getString("area"),
                    rs.getBoolean("ativa")
                ));
            }
        }
        return lista;
    }

    public List<Vaga> listarAtivas() throws SQLException {
        List<Vaga> lista = new ArrayList<>();
        String sql = "SELECT id, empresa_id, titulo, descricao, area, ativa FROM vagas WHERE ativa=true ORDER BY titulo";
        try (Connection con = ConexaoBD.getConnection();
             Statement st  = con.createStatement();
             ResultSet rs  = st.executeQuery(sql)) {
            while (rs.next()) {
                lista.add(new Vaga(
                    rs.getInt("id"),
                    rs.getInt("empresa_id"),
                    rs.getString("titulo"),
                    rs.getString("descricao"),
                    rs.getString("area"),
                    rs.getBoolean("ativa")
                ));
            }
        }
        return lista;
    }

    public void salvar(Vaga v) throws SQLException {
        String sql = "INSERT INTO vagas (empresa_id, titulo, descricao, area, ativa) VALUES (?,?,?,?,?)";
        try (Connection con = ConexaoBD.getConnection();
             PreparedStatement ps = con.prepareStatement(sql)) {
            ps.setInt(1, v.getEmpresaId());
            ps.setString(2, v.getTitulo());
            ps.setString(3, v.getDescricao());
            ps.setString(4, v.getArea());
            ps.setBoolean(5, v.isAtiva());
            ps.executeUpdate();
        }
    }

    public void alterarAtiva(int id, boolean ativa) throws SQLException {
        String sql = "UPDATE vagas SET ativa=? WHERE id=?";
        try (Connection con = ConexaoBD.getConnection();
             PreparedStatement ps = con.prepareStatement(sql)) {
            ps.setBoolean(1, ativa);
            ps.setInt(2, id);
            ps.executeUpdate();
        }
    }
}
