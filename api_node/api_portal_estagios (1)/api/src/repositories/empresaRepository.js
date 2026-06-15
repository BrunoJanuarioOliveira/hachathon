const pool = require('./db');

// Lista todas as empresas
async function listarEmpresas() {
  const [rows] = await pool.query('SELECT * FROM empresas ORDER BY nome ASC');
  return rows;
}

// Busca empresa por ID
async function buscarEmpresaPorId(id) {
  const [rows] = await pool.query('SELECT * FROM empresas WHERE id = ?', [id]);
  return rows[0] || null;
}

// Cadastra nova empresa
async function criarEmpresa(dados) {
  const { nome, cnpj, email, telefone, descricao } = dados;
  const sql = 'INSERT INTO empresas (nome, cnpj, email, telefone, descricao, status) VALUES (?, ?, ?, ?, ?, "pendente")';
  const [result] = await pool.query(sql, [nome, cnpj, email, telefone, descricao]);
  return result.insertId;
}

// Atualiza status da empresa (pendente, aprovada, bloqueada)
async function atualizarStatusEmpresa(id, status) {
  await pool.query('UPDATE empresas SET status = ? WHERE id = ?', [status, id]);
}

module.exports = { listarEmpresas, buscarEmpresaPorId, criarEmpresa, atualizarStatusEmpresa };
