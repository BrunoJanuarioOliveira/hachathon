const vagaService = require('../services/vagaService');

// GET /api/vagas
async function listar(req, res) {
  try {
    const vagas = await vagaService.listarVagas();
    res.json(vagas); // status 200 implicito
  } catch (err) {
    res.status(500).json({ erro: err.message });
  }
}

// POST /api/vagas
async function criar(req, res) {
  try {
    const vaga = await vagaService.criarVaga(req.body);
    res.status(201).json(vaga); // 201 = Created
  } catch (err) {
    const status = err.name === 'ZodError' ? 422 : 500;
    res.status(status).json({ erro: err.message });
  }
}

// PUT /api/vagas/:id
async function atualizar(req, res) {
  try {
    const vaga = await vagaService.atualizarVaga(Number(req.params.id), req.body);
    res.json(vaga);
  } catch (err) {
    const status = err.name === 'ZodError' ? 422 : (err.message.includes('não encontrada') ? 404 : 500);
    res.status(status).json({ erro: err.message });
  }
}

// DELETE /api/vagas/:id
async function deletar(req, res) {
  try {
    await vagaService.deletarVaga(Number(req.params.id));
    res.json({ mensagem: 'Vaga encerrada com sucesso' });
  } catch (err) {
    const status = err.message.includes('não encontrada') ? 404 : 500;
    res.status(status).json({ erro: err.message });
  }
}

module.exports = { listar, criar, atualizar, deletar };
