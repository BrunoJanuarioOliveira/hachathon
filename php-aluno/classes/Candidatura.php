<?php
require_once __DIR__ . '/Entidade.php';

class Candidatura extends Entidade {
    private int $vagaId;
    private string $tituloVaga;
    private string $empresaNome;
    private string $status;
    private string $criadoEm;

    public function __construct(array $d) {
        $this->id = (int) ($d['id'] ?? 0);
        $this->vagaId = (int) ($d['vaga_id'] ?? 0);
        $this->tituloVaga = (string) ($d['titulo'] ?? '');
        $this->empresaNome = (string) ($d['empresa_nome'] ?? '');
        $this->status = (string) ($d['status'] ?? 'enviada');
        $this->criadoEm = (string) ($d['criado_em'] ?? '');
    }

    public function getTituloVaga(): string {
        return $this->tituloVaga;
    }

    public function getEmpresaNome(): string {
        return $this->empresaNome;
    }

    public function getStatus(): string {
        return $this->status;
    }

    public function getCriadoEm(): string {
        return $this->criadoEm;
    }

    public function getStatusBadge(): string {
        return match ($this->status) {
            'aprovada' => 'badge-green',
            'reprovada' => 'badge-red',
            'em_analise' => 'badge-amber',
            default => 'badge-blue',
        };
    }

    public function getStatusTexto(): string {
        return match ($this->status) {
            'aprovada' => 'Aprovada',
            'reprovada' => 'Reprovada',
            'em_analise' => 'Em análise',
            default => 'Enviada',
        };
    }

    public function getResumo(): string {
        return $this->tituloVaga . ' [' . $this->status . ']';
    }
}
