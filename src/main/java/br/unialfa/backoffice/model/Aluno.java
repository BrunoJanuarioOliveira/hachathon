package br.unialfa.backoffice.model;

public class Aluno {

    private int id;
    private String nome;
    private String ra;
    private String email;
    private boolean apto;

    public Aluno() {}

    public Aluno(int id, String nome, String ra, String email, boolean apto) {
        this.id    = id;
        this.nome  = nome;
        this.ra    = ra;
        this.email = email;
        this.apto  = apto;
    }

    public int     getId()    { return id; }
    public String  getNome()  { return nome; }
    public String  getRa()    { return ra; }
    public String  getEmail() { return email; }
    public boolean isApto()   { return apto; }

    public void setId(int id)          { this.id    = id; }
    public void setNome(String nome)   { this.nome  = nome; }
    public void setRa(String ra)       { this.ra    = ra; }
    public void setEmail(String email) { this.email = email; }
    public void setApto(boolean apto)  { this.apto  = apto; }

    @Override
    public String toString() { return nome + " (" + ra + ")"; }
}
