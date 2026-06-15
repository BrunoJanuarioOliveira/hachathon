package br.unialfa.backoffice.util;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;

public class ConexaoBD {

    // Lê configurações de variáveis de ambiente; cai nos valores padrão se não definidas
    private static final String DB_HOST = System.getenv().getOrDefault("DB_HOST", "localhost");
    private static final String DB_NAME = System.getenv().getOrDefault("DB_NAME", "portal_estagios");
    private static final String URL  = "jdbc:mysql://" + DB_HOST + ":3306/" + DB_NAME
                                       + "?useSSL=false&serverTimezone=America/Sao_Paulo";
    private static final String USER = System.getenv().getOrDefault("DB_USER", "root");
    private static final String PASS = System.getenv().getOrDefault("DB_PASS", ""); // defina DB_PASS no ambiente

    public static Connection getConnection() throws SQLException {
        return DriverManager.getConnection(URL, USER, PASS);
    }

    /** Testa a conexão — chame no main para verificar antes de abrir a GUI */
    public static boolean testarConexao() {
        try (Connection c = getConnection()) {
            return c != null && !c.isClosed();
        } catch (SQLException e) {
            return false;
        }
    }
}
