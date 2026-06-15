<?php
class ApiClient {
    private string $baseUrl;

    public function __construct(string $baseUrl = 'http://localhost:8081/api.php') {
        $this->baseUrl = rtrim($baseUrl, '/');

        if (!function_exists('curl_version')) {
            throw new RuntimeException('A extensao cURL nao esta habilitada no PHP.');
        }
    }

    private function requisitar(string $metodo, string $rota, array $dados = []): array {
        $url = $this->baseUrl . $rota;
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json',
        ]);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $metodo);

        if (!empty($dados) && in_array($metodo, ['POST', 'PUT', 'PATCH'])) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dados));
        }

        $resposta = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlErro = curl_error($ch);
        curl_close($ch);

        if ($resposta === false) {
            throw new RuntimeException('Erro ao conectar na API: ' . $curlErro);
        }

        $json = json_decode($resposta, true);
        if ($json === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException('Resposta invalida da API: ' . json_last_error_msg());
        }

        if ($httpCode >= 400) {
            $erro = $json['erro'] ?? ('HTTP ' . $httpCode);
            throw new RuntimeException('Erro HTTP na API: ' . $erro);
        }

        return $json;
    }

    public function getVagas(): array {
        return $this->requisitar('GET', '?acao=vagas');
    }

    public function getAlunoPorRa(string $ra): array {
        return $this->requisitar('GET', '?acao=aluno&ra=' . urlencode($ra));
    }

    public function candidatar(int $alunoId, int $vagaId): array {
        return $this->requisitar('POST', '?acao=candidatar', [
            'aluno_id' => $alunoId,
            'vaga_id' => $vagaId,
        ]);
    }

    public function getCandidaturasAluno(int $alunoId): array {
        return $this->requisitar('GET', '?acao=candidaturas&aluno_id=' . $alunoId);
    }

    public function getNotificacoesAluno(int $alunoId): array {
        return $this->requisitar('GET', '?acao=notificacoes&aluno_id=' . $alunoId);
    }

    public function marcarNotificacaoLida(int $id): array {
        return $this->requisitar('PATCH', '?acao=notificacao_lida&id=' . $id);
    }
}
