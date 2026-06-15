const vagaRepo = require('../repositories/vagaRepository');
const { z } = require('zod');

// Schema Zod: define como os dados DEVEM ser para criar vaga
const vagaSchema = z.object({
  empresa_id: z.number().int().positive(),        // numero inteiro positivo
  titulo:     z.string().min(3).max(200),          // string entre 3 e 200 chars
  descricao:  z.string().optional(),               // campo opcional
  area:       z.string().optional(),
  bolsa:      z.number().nonnegative().optional(), // numero >= 0 ou ausente
});

// Schema para atualizacao (todos os campos exceto empresa_id)
const vagaUpdateSchema = z.object({
  titulo:    z.string().min(3).max(200),
  descricao: z.string().optional(),
  area:      z.string().optional(),
  bolsa:     z.number().nonnegative().optional(),
  ativa:     z.number().int().min(0).max(1).optional(), // 0 ou 1
});

async function listarVagas() {
  return vagaRepo.listarVagasAtivas();
}

async function criarVaga(dados) {
  vagaSchema.parse(dados); // VALIDA — lanca erro se invalido
  const id = await vagaRepo.criarVaga(dados);
  return { id, ...dados };
}

async function atualizarVaga(id, dados) {
  vagaUpdateSchema.parse(dados);
  const existente = await vagaRepo.buscarVagaPorId(id);
  if (!existente) throw new Error('Vaga não encontrada');
  await vagaRepo.atualizarVaga(id, dados);
  return { id, ...dados };
}

async function deletarVaga(id) {
  const existente = await vagaRepo.buscarVagaPorId(id);
  if (!existente) throw new Error('Vaga não encontrada');
  await vagaRepo.deletarVaga(id);
}

module.exports = { listarVagas, criarVaga, atualizarVaga, deletarVaga };
