const empresaService = require('../services/empresaService');

// GET /api/empresas
async function listar(req, res) {
  try {
    const empresas = await empresaService.listarEmpresas();
    res.json(empresas);
  } catch (err) {
    res.status(500).json({ erro: err.message });
  }
}

// POST /api/empresas
async function criar(req, res) {
  try {
    const empresa = await empresaService.criarEmpresa(req.body);
    res.status(201).json(empresa);
  } catch (err) {
    const status = err.name === 'ZodError' ? 422 : 500;
    res.status(status).json({ erro: err.message });
  }
}

// PATCH /api/empresas/:id/status
async function atualizarStatus(req, res) {
  try {
    const result = await empresaService.atualizarStatusEmpresa(Number(req.params.id), req.body);
    res.json(result);
  } catch (err) {
    const status = err.name === 'ZodError' ? 422 : (err.message.includes('não encontrada') ? 404 : 500);
    res.status(status).json({ erro: err.message });
  }
}

module.exports = { listar, criar, atualizarStatus };
