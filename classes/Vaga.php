<?php
require_once __DIR__ . '/Entidade.php';

class Vaga extends Entidade {
    private string $titulo, $empresaNome, $area, $descricao;
    private float  $bolsa;

    public function __construct(array $d) {
        $this->id          = (int)   ($d['id']           ?? 0);
        $this->titulo      = (string)($d['titulo']       ?? '');
        $this->empresaNome = (string)($d['empresa_nome'] ?? '');
        $this->area        = (string)($d['area']         ?? '');
        $this->bolsa       = (float) ($d['bolsa']        ?? 0);
        $this->descricao   = (string)($d['descricao']    ?? '');
    }

    public function getTitulo():      string { return $this->titulo; }
    public function getEmpresaNome(): string { return $this->empresaNome; }
    public function getArea():        string { return $this->area; }
    public function getBolsa():       float  { return $this->bolsa; }
    public function getDescricao():   string { return $this->descricao; }
    public function getBolsaFormatada(): string {
        return 'R$ ' . number_format($this->bolsa, 2, ',', '.');
    }

    public function getResumo(): string {
        return $this->titulo . ' — ' . $this->empresaNome;
    }
}
