package br.unialfa.backoffice.dao;

import br.unialfa.backoffice.model.Aluno;
import br.unialfa.backoffice.util.ConexaoBD;

import java.sql.*;
import java.util.ArrayList;
import java.util.List;

public class AlunoDAO {

    public List<Aluno> listarTodos() throws SQLException {
        List<Aluno> lista = new ArrayList<>();
        String sql = "SELECT id, nome, ra, email, apto FROM alunos ORDER BY nome";
        try (Connection con = ConexaoBD.getConnection();
             Statement st  = con.createStatement();
             ResultSet rs  = st.executeQuery(sql)) {
            while (rs.next()) {
                lista.add(new Aluno(
                    rs.getInt("id"),
                    rs.getString("nome"),
                    rs.getString("ra"),
                    rs.getString("email"),
                    rs.getBoolean("apto")
                ));
            }
        }
        return lista;
    }

    public void salvar(Aluno a) throws SQLException {
        String sql = "INSERT INTO alunos (nome, ra, email, senha_hash, apto) VALUES (?,?,?,?,?)";
        try (Connection con = ConexaoBD.getConnection();
             PreparedStatement ps = con.prepareStatement(sql)) {
            ps.setString(1, a.getNome());
            ps.setString(2, a.getRa());
            ps.setString(3, a.getEmail());
            ps.setString(4, "sem_senha");
            ps.setBoolean(5, a.isApto());
            ps.executeUpdate();
        }
    }

    public void atualizar(Aluno a) throws SQLException {
        String sql = "UPDATE alunos SET nome=?, ra=?, email=?, apto=? WHERE id=?";
        try (Connection con = ConexaoBD.getConnection();
             PreparedStatement ps = con.prepareStatement(sql)) {
            ps.setString(1, a.getNome());
            ps.setString(2, a.getRa());
            ps.setString(3, a.getEmail());
            ps.setBoolean(4, a.isApto());
            ps.setInt(5, a.getId());
            ps.executeUpdate();
        }
    }

    public void alterarApto(int id, boolean apto) throws SQLException {
        String sql = "UPDATE alunos SET apto=? WHERE id=?";
        try (Connection con = ConexaoBD.getConnection();
             PreparedStatement ps = con.prepareStatement(sql)) {
            ps.setBoolean(1, apto);
            ps.setInt(2, id);
            ps.executeUpdate();
        }
    }

    public void excluir(int id) throws SQLException {
        String sql = "DELETE FROM alunos WHERE id=?";
        try (Connection con = ConexaoBD.getConnection();
             PreparedStatement ps = con.prepareStatement(sql)) {
            ps.setInt(1, id);
            ps.executeUpdate();
        }
    }
}
