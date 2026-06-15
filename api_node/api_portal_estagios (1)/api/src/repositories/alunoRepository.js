const pool = require('./db');

// Lista todos os alunos
async function listarAlunos() {
  const [rows] = await pool.query('SELECT id, nome, email, curso, periodo, criado_em FROM alunos ORDER BY nome ASC');
  return rows;
}

// Busca aluno por ID
async function buscarAlunoPorId(id) {
  const [rows] = await pool.query(
    'SELECT id, nome, email, curso, periodo, criado_em FROM alunos WHERE id = ?',
    [id]
  );
  return rows[0] || null;
}

// Cadastra novo aluno
async function criarAluno(dados) {
  const { nome, email, senha, curso, periodo } = dados;
  const sql = 'INSERT INTO alunos (nome, email, senha, curso, periodo) VALUES (?, ?, ?, ?, ?)';
  const [result] = await pool.query(sql, [nome, email, senha, curso, periodo]);
  return result.insertId;
}

// Atualiza dados do aluno
async function atualizarAluno(id, dados) {
  const { nome, email, curso, periodo } = dados;
  const sql = 'UPDATE alunos SET nome=?, email=?, curso=?, periodo=? WHERE id=?';
  await pool.query(sql, [nome, email, curso, periodo, id]);
}

module.exports = { listarAlunos, buscarAlunoPorId, criarAluno, atualizarAluno };
