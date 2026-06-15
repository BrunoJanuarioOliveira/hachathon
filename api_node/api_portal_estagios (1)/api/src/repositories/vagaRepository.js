const pool = require('./db');

// Retorna vagas ativas com nome da empresa (JOIN)
async function listarVagasAtivas() {
  const sql = `
    SELECT v.*, e.nome AS empresa_nome
    FROM vagas v
    JOIN empresas e ON e.id = v.empresa_id
    WHERE v.ativa = 1 AND e.status = 'aprovada'
    ORDER BY v.criado_em DESC
  `;
  const [rows] = await pool.query(sql);
  return rows;
}

// Busca vaga por ID
async function buscarVagaPorId(id) {
  const [rows] = await pool.query('SELECT * FROM vagas WHERE id = ?', [id]);
  return rows[0] || null;
}

// Insere nova vaga e retorna o ID gerado
async function criarVaga(dados) {
  const { empresa_id, titulo, descricao, area, bolsa } = dados;
  const sql = 'INSERT INTO vagas (empresa_id, titulo, descricao, area, bolsa) VALUES (?, ?, ?, ?, ?)';
  const [result] = await pool.query(sql, [empresa_id, titulo, descricao, area, bolsa]);
  return result.insertId;
}

// Atualiza dados de uma vaga
async function atualizarVaga(id, dados) {
  const { titulo, descricao, area, bolsa, ativa } = dados;
  const sql = 'UPDATE vagas SET titulo=?, descricao=?, area=?, bolsa=?, ativa=? WHERE id=?';
  await pool.query(sql, [titulo, descricao, area, bolsa, ativa, id]);
}

// Exclusao logica: marca como inativa em vez de apagar do banco
async function deletarVaga(id) {
  await pool.query('UPDATE vagas SET ativa=0 WHERE id=?', [id]);
}

module.exports = { listarVagasAtivas, buscarVagaPorId, criarVaga, atualizarVaga, deletarVaga };
