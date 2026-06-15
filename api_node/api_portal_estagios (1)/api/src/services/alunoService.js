const alunoRepo = require('../repositories/alunoRepository');
const { z } = require('zod');

const alunoSchema = z.object({
  nome:    z.string().min(2).max(150),
  email:   z.string().email(),
  senha:   z.string().min(6),
  curso:   z.string().optional(),
  periodo: z.number().int().min(1).max(10).optional(),
});

const alunoUpdateSchema = z.object({
  nome:    z.string().min(2).max(150),
  email:   z.string().email(),
  curso:   z.string().optional(),
  periodo: z.number().int().min(1).max(10).optional(),
});

async function listarAlunos() {
  return alunoRepo.listarAlunos();
}

async function buscarAluno(id) {
  const aluno = await alunoRepo.buscarAlunoPorId(id);
  if (!aluno) throw new Error('Aluno não encontrado');
  return aluno;
}

async function criarAluno(dados) {
  alunoSchema.parse(dados);
  const id = await alunoRepo.criarAluno(dados);
  const { senha, ...retorno } = dados; // nunca retorna a senha
  return { id, ...retorno };
}

async function atualizarAluno(id, dados) {
  alunoUpdateSchema.parse(dados);
  const existente = await alunoRepo.buscarAlunoPorId(id);
  if (!existente) throw new Error('Aluno não encontrado');
  await alunoRepo.atualizarAluno(id, dados);
  return { id, ...dados };
}

module.exports = { listarAlunos, buscarAluno, criarAluno, atualizarAluno };
