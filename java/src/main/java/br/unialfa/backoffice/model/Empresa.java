package br.unialfa.backoffice.model;

public class Empresa {

    private int    id;
    private String nome;
    private String cnpj;
    private String email;
    private String status;

    public Empresa() {}

    public Empresa(int id, String nome, String cnpj, String email, String status) {
        this.id       = id;
        this.nome     = nome;
        this.cnpj     = cnpj;
        this.email    = email;
        this.status   = status;
    }

    public int     getId()       { return id; }
    public String  getNome()     { return nome; }
    public String  getCnpj()     { return cnpj; }
    public String  getEmail()    { return email; }
    public String  getStatus()   { return status; }

    public void setId(int id)              { this.id       = id; }
    public void setNome(String nome)       { this.nome     = nome; }
    public void setCnpj(String cnpj)       { this.cnpj     = cnpj; }
    public void setEmail(String email)     { this.email    = email; }
    public void setStatus(String status)   { this.status   = status; }

    @Override
    public String toString() { return nome + " (" + cnpj + ")"; }
}
