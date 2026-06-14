package br.unialfa.backoffice.model;

public class Empresa {

    private int    id;
    private String nome;
    private String cnpj;
    private String email;
    private String telefone;
    private boolean aprovada;

    public Empresa() {}

    public Empresa(int id, String nome, String cnpj, String email, String telefone, boolean aprovada) {
        this.id       = id;
        this.nome     = nome;
        this.cnpj     = cnpj;
        this.email    = email;
        this.telefone = telefone;
        this.aprovada = aprovada;
    }

    public int     getId()       { return id; }
    public String  getNome()     { return nome; }
    public String  getCnpj()     { return cnpj; }
    public String  getEmail()    { return email; }
    public String  getTelefone() { return telefone; }
    public boolean isAprovada()  { return aprovada; }

    public void setId(int id)              { this.id       = id; }
    public void setNome(String nome)       { this.nome     = nome; }
    public void setCnpj(String cnpj)       { this.cnpj     = cnpj; }
    public void setEmail(String email)     { this.email    = email; }
    public void setTelefone(String t)      { this.telefone = t; }
    public void setAprovada(boolean a)     { this.aprovada = a; }

    @Override
    public String toString() { return nome + " (" + cnpj + ")"; }
}
