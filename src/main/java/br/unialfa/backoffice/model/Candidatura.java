package br.unialfa.backoffice.model;

import java.time.LocalDate;

public class Candidatura {

    private int       id;
    private int       alunoId;
    private int       vagaId;
    private String    nomeAluno;   // join — facilita exibição na GUI
    private String    tituloVaga;  // join — facilita exibição na GUI
    private LocalDate dataCandidatura;
    private String    status;      // "pendente", "aprovado", "reprovado"

    public Candidatura() {}

    public Candidatura(int id, int alunoId, int vagaId, String nomeAluno,
                       String tituloVaga, LocalDate dataCandidatura, String status) {
        this.id               = id;
        this.alunoId          = alunoId;
        this.vagaId           = vagaId;
        this.nomeAluno        = nomeAluno;
        this.tituloVaga       = tituloVaga;
        this.dataCandidatura  = dataCandidatura;
        this.status           = status;
    }

    public int       getId()              { return id; }
    public int       getAlunoId()         { return alunoId; }
    public int       getVagaId()          { return vagaId; }
    public String    getNomeAluno()       { return nomeAluno; }
    public String    getTituloVaga()      { return tituloVaga; }
    public LocalDate getDataCandidatura() { return dataCandidatura; }
    public String    getStatus()          { return status; }

    public void setId(int id)                        { this.id              = id; }
    public void setAlunoId(int alunoId)              { this.alunoId         = alunoId; }
    public void setVagaId(int vagaId)                { this.vagaId          = vagaId; }
    public void setNomeAluno(String n)               { this.nomeAluno       = n; }
    public void setTituloVaga(String t)              { this.tituloVaga      = t; }
    public void setDataCandidatura(LocalDate d)      { this.dataCandidatura = d; }
    public void setStatus(String status)             { this.status          = status; }

    @Override
    public String toString() { return nomeAluno + " → " + tituloVaga + " [" + status + "]"; }
}
