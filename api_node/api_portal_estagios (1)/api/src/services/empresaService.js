const empresaRepo = require('../repositories/empresaRepository');
const { z } = require('zod');

const empresaSchema = z.object({
  nome:      z.string().min(2).max(200),
  cnpj:      z.string().length(18),          // formato: XX.XXX.XXX/XXXX-XX
  email:     z.string().email(),
  telefone:  z.string().optional(),
  descricao: z.string().optional(),
});

const statusSchema = z.object({
  status: z.enum(['pendente', 'aprovada', 'bloqueada']),
});

async function listarEmpresas() {
  return empresaRepo.listarEmpresas();
}

async function criarEmpresa(dados) {
  empresaSchema.parse(dados);
  const id = await empresaRepo.criarEmpresa(dados);
  return { id, ...dados, status: 'pendente' };
}

async function atualizarStatusEmpresa(id, dados) {
  statusSchema.parse(dados);
  const existente = await empresaRepo.buscarEmpresaPorId(id);
  if (!existente) throw new Error('Empresa não encontrada');
  await empresaRepo.atualizarStatusEmpresa(id, dados.status);
  return { id, ...dados };
}

module.exports = { listarEmpresas, criarEmpresa, atualizarStatusEmpresa };
