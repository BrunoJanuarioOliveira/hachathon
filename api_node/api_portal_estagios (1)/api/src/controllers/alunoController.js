const alunoService = require('../services/alunoService');

// GET /api/alunos
async function listar(req, res) {
  try {
    const alunos = await alunoService.listarAlunos();
    res.json(alunos);
  } catch (err) {
    res.status(500).json({ erro: err.message });
  }
}

// GET /api/alunos/:id
async function buscar(req, res) {
  try {
    const aluno = await alunoService.buscarAluno(Number(req.params.id));
    res.json(aluno);
  } catch (err) {
    const status = err.message.includes('não encontrado') ? 404 : 500;
    res.status(status).json({ erro: err.message });
  }
}

// POST /api/alunos
async function criar(req, res) {
  try {
    const aluno = await alunoService.criarAluno(req.body);
    res.status(201).json(aluno);
  } catch (err) {
    const status = err.name === 'ZodError' ? 422 : 500;
    res.status(status).json({ erro: err.message });
  }
}

// PUT /api/alunos/:id
async function atualizar(req, res) {
  try {
    const aluno = await alunoService.atualizarAluno(Number(req.params.id), req.body);
    res.json(aluno);
  } catch (err) {
    const status = err.name === 'ZodError' ? 422 : (err.message.includes('não encontrado') ? 404 : 500);
    res.status(status).json({ erro: err.message });
  }
}

module.exports = { listar, buscar, criar, atualizar };
