package br.unialfa.backoffice.model;

public class Vaga {

    private int    id;
    private int    empresaId;
    private String titulo;
    private String descricao;
    private String area;
    private boolean ativa;

    public Vaga() {}

    public Vaga(int id, int empresaId, String titulo, String descricao, String area, boolean ativa) {
        this.id        = id;
        this.empresaId = empresaId;
        this.titulo    = titulo;
        this.descricao = descricao;
        this.area      = area;
        this.ativa     = ativa;
    }

    public int     getId()        { return id; }
    public int     getEmpresaId() { return empresaId; }
    public String  getTitulo()    { return titulo; }
    public String  getDescricao() { return descricao; }
    public String  getArea()      { return area; }
    public boolean isAtiva()      { return ativa; }

    public void setId(int id)              { this.id        = id; }
    public void setEmpresaId(int eId)      { this.empresaId = eId; }
    public void setTitulo(String titulo)   { this.titulo    = titulo; }
    public void setDescricao(String d)     { this.descricao = d; }
    public void setArea(String area)       { this.area      = area; }
    public void setAtiva(boolean ativa)    { this.ativa     = ativa; }

    @Override
    public String toString() { return titulo + " — " + area; }
}
