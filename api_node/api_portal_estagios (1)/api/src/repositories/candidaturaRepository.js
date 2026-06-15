const pool = require('./db');

// Lista candidaturas com filtro opcional por aluno_id ou vaga_id
async function listarCandidaturas(filtros = {}) {
  let sql = `
    SELECT c.*, a.nome AS aluno_nome, v.titulo AS vaga_titulo
    FROM candidaturas c
    JOIN alunos a ON a.id = c.aluno_id
    JOIN vagas  v ON v.id = c.vaga_id
    WHERE 1=1
  `;
  const params = [];

  if (filtros.aluno_id) {
    sql += ' AND c.aluno_id = ?';
    params.push(filtros.aluno_id);
  }
  if (filtros.vaga_id) {
    sql += ' AND c.vaga_id = ?';
    params.push(filtros.vaga_id);
  }

  sql += ' ORDER BY c.criado_em DESC';
  const [rows] = await pool.query(sql, params);
  return rows;
}

// Verifica se candidatura ja existe (evita duplicidade)
async function buscarCandidatura(aluno_id, vaga_id) {
  const [rows] = await pool.query(
    'SELECT * FROM candidaturas WHERE aluno_id = ? AND vaga_id = ?',
    [aluno_id, vaga_id]
  );
  return rows[0] || null;
}

// Cria candidatura e retorna ID gerado
async function criarCandidatura(dados) {
  const { aluno_id, vaga_id } = dados;
  const sql = 'INSERT INTO candidaturas (aluno_id, vaga_id, status) VALUES (?, ?, "pendente")';
  const [result] = await pool.query(sql, [aluno_id, vaga_id]);
  return result.insertId;
}

// Atualiza status da candidatura (pendente, aprovado, reprovado)
async function atualizarStatusCandidatura(id, status) {
  await pool.query('UPDATE candidaturas SET status = ? WHERE id = ?', [status, id]);
}

module.exports = { listarCandidaturas, buscarCandidatura, criarCandidatura, atualizarStatusCandidatura };
