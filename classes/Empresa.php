<?php
require_once __DIR__ . '/Entidade.php';

class Empresa extends Entidade {
    private string $nome, $cnpj, $email, $status;

    public function __construct(array $d) {
        $this->id     = (int)   ($d['id']     ?? 0);
        $this->nome   = (string)($d['nome']   ?? '');
        $this->cnpj   = (string)($d['cnpj']   ?? '');
        $this->email  = (string)($d['email']  ?? '');
        $this->status = (string)($d['status'] ?? 'pendente');
    }

    public function getNome():      string { return $this->nome; }
    public function getCnpj():      string { return $this->cnpj; }
    public function getEmail():     string { return $this->email; }
    public function getStatus():    string { return $this->status; }
    public function estaAprovada(): bool   { return $this->status === 'aprovada'; }

    public function getResumo(): string {
        return $this->nome . ' [' . $this->status . ']';
    }
}
