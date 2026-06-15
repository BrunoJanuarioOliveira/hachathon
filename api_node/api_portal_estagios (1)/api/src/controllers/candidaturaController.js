const candidaturaService = require('../services/candidaturaService');

// GET /api/candidaturas?aluno_id=X ou ?vaga_id=Y
async function listar(req, res) {
  try {
    const candidaturas = await candidaturaService.listarCandidaturas(req.query);
    res.json(candidaturas);
  } catch (err) {
    res.status(500).json({ erro: err.message });
  }
}

// POST /api/candidaturas
async function criar(req, res) {
  try {
    const candidatura = await candidaturaService.criarCandidatura(req.body);
    res.status(201).json(candidatura);
  } catch (err) {
    const status = err.name === 'ZodError' ? 422 : (err.message.includes('já se candidatou') ? 409 : 500);
    res.status(status).json({ erro: err.message });
  }
}

// PATCH /api/candidaturas/:id
async function atualizarStatus(req, res) {
  try {
    const result = await candidaturaService.atualizarStatusCandidatura(Number(req.params.id), req.body);
    res.json(result);
  } catch (err) {
    const status = err.name === 'ZodError' ? 422 : 500;
    res.status(status).json({ erro: err.message });
  }
}

module.exports = { listar, criar, atualizarStatus };
