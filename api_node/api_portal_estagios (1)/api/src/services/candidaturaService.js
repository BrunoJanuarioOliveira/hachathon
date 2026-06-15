const candidaturaRepo = require('../repositories/candidaturaRepository');
const { z } = require('zod');

const candidaturaSchema = z.object({
  aluno_id: z.number().int().positive(),
  vaga_id:  z.number().int().positive(),
});

const statusSchema = z.object({
  status: z.enum(['pendente', 'aprovado', 'reprovado']),
});

async function listarCandidaturas(query) {
  const filtros = {};
  if (query.aluno_id) filtros.aluno_id = Number(query.aluno_id);
  if (query.vaga_id)  filtros.vaga_id  = Number(query.vaga_id);
  return candidaturaRepo.listarCandidaturas(filtros);
}

async function criarCandidatura(dados) {
  candidaturaSchema.parse(dados);

  // Regra de negocio: nao permite candidatura duplicada
  const jaExiste = await candidaturaRepo.buscarCandidatura(dados.aluno_id, dados.vaga_id);
  if (jaExiste) throw new Error('Aluno já se candidatou a esta vaga');

  const id = await candidaturaRepo.criarCandidatura(dados);
  return { id, ...dados, status: 'pendente' };
}

async function atualizarStatusCandidatura(id, dados) {
  statusSchema.parse(dados);
  await candidaturaRepo.atualizarStatusCandidatura(id, dados.status);
  return { id, ...dados };
}

module.exports = { listarCandidaturas, criarCandidatura, atualizarStatusCandidatura };
