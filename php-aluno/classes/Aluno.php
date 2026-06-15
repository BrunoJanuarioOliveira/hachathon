<?php
require_once __DIR__ . '/Entidade.php';

class Aluno extends Entidade {
    private string $nome;
    private string $ra;
    private string $email;
    private bool $apto;

    public function __construct(array $d) {
        $this->id = (int) ($d['id'] ?? 0);
        $this->nome = (string) ($d['nome'] ?? '');
        $this->ra = (string) ($d['ra'] ?? '');
        $this->email = (string) ($d['email'] ?? '');
        $this->apto = (bool) ($d['apto'] ?? true);
    }

    public function getNome(): string {
        return $this->nome;
    }

    public function getRa(): string {
        return $this->ra;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function isApto(): bool {
        return $this->apto;
    }

    public function getResumo(): string {
        return $this->nome . ' [RA: ' . $this->ra . ']';
    }
}
