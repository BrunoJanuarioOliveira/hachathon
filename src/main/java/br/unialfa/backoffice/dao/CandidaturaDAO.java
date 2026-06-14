package br.unialfa.backoffice.dao;

import br.unialfa.backoffice.model.Candidatura;
import br.unialfa.backoffice.util.ConexaoBD;

import java.sql.*;
import java.util.ArrayList;
import java.util.List;

public class CandidaturaDAO {

    public List<Candidatura> listarTodas() throws SQLException {
        List<Candidatura> lista = new ArrayList<>();
        String sql = """
            SELECT c.id, c.aluno_id, c.vaga_id,
                   a.nome AS nome_aluno, v.titulo AS titulo_vaga,
                   c.data_candidatura, c.status
            FROM candidaturas c
            JOIN alunos a ON a.id = c.aluno_id
            JOIN vagas  v ON v.id = c.vaga_id
            ORDER BY c.data_candidatura DESC
            """;
        try (Connection con = ConexaoBD.getConnection();
             Statement st  = con.createStatement();
             ResultSet rs  = st.executeQuery(sql)) {
            while (rs.next()) {
                Candidatura cand = new Candidatura(
                    rs.getInt("id"),
                    rs.getInt("aluno_id"),
                    rs.getInt("vaga_id"),
                    rs.getString("nome_aluno"),
                    rs.getString("titulo_vaga"),
                    rs.getDate("data_candidatura").toLocalDate(),
                    rs.getString("status")
                );
                lista.add(cand);
            }
        }
        return lista;
    }

    public List<Candidatura> listarPorVaga(int vagaId) throws SQLException {
        List<Candidatura> lista = new ArrayList<>();
        String sql = """
            SELECT c.id, c.aluno_id, c.vaga_id,
                   a.nome AS nome_aluno, v.titulo AS titulo_vaga,
                   c.data_candidatura, c.status
            FROM candidaturas c
            JOIN alunos a ON a.id = c.aluno_id
            JOIN vagas  v ON v.id = c.vaga_id
            WHERE c.vaga_id = ?
            ORDER BY c.data_candidatura DESC
            """;
        try (Connection con = ConexaoBD.getConnection();
             PreparedStatement ps = con.prepareStatement(sql)) {
            ps.setInt(1, vagaId);
            try (ResultSet rs = ps.executeQuery()) {
                while (rs.next()) {
                    lista.add(new Candidatura(
                        rs.getInt("id"),
                        rs.getInt("aluno_id"),
                        rs.getInt("vaga_id"),
                        rs.getString("nome_aluno"),
                        rs.getString("titulo_vaga"),
                        rs.getDate("data_candidatura").toLocalDate(),
                        rs.getString("status")
                    ));
                }
            }
        }
        return lista;
    }

    public void alterarStatus(int id, String status) throws SQLException {
        String sql = "UPDATE candidaturas SET status=? WHERE id=?";
        try (Connection con = ConexaoBD.getConnection();
             PreparedStatement ps = con.prepareStatement(sql)) {
            ps.setString(1, status);
            ps.setInt(2, id);
            ps.executeUpdate();
        }
    }
}
