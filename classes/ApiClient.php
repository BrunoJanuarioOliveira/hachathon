<?php
class ApiClient {
    private string $baseUrl = 'http://localhost:3000/api';

    private function req(string $m, string $r, array $d = []): array {
        if (!function_exists('curl_init'))
            throw new RuntimeException('cURL nao ativo. Ative no php.ini: extension=curl');
        $ch = curl_init($this->baseUrl . $r);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Accept: application/json']);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $m);
        if (!empty($d) && in_array($m, ['POST','PUT','PATCH']))
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($d));
        $resp = curl_exec($ch); curl_close($ch);
        return json_decode($resp, true) ?? [];
    }

    public function getEmpresaPorEmail(string $e): array   { return $this->req('GET', '/empresas?email=' . urlencode($e)); }
    public function cadastrarEmpresa(array $d): array      { return $this->req('POST', '/empresas', $d); }
    public function getVagasEmpresa(int $id): array        { return $this->req('GET', '/vagas?empresa_id=' . $id); }
    public function getVagaPorId(int $id): array           { return $this->req('GET', '/vagas/' . $id); }
    public function criarVaga(array $d): array             { return $this->req('POST', '/vagas', $d); }
    public function atualizarVaga(int $id, array $d): array{ return $this->req('PUT', '/vagas/' . $id, $d); }
    public function deletarVaga(int $id): array            { return $this->req('DELETE', '/vagas/' . $id); }
    public function getCandidatosPorVaga(int $id): array   { return $this->req('GET', '/candidaturas?vaga_id=' . $id); }
    public function atualizarStatusCandidatura(int $id, string $s): array {
        return $this->req('PATCH', '/candidaturas/' . $id . '/status', ['status' => $s]);
    }
}
