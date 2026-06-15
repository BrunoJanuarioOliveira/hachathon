// src/services/vagaService.ts
import { z } from 'zod';
import * as repo from '../repositories/vagaRepository';
import { Vaga } from '../types';

const schema = z.object({
  empresa_id: z.number().int().positive(),
  titulo:     z.string().min(3).max(200),
  descricao:  z.string().optional().default(''),
  area:       z.string().optional().default(''),
  bolsa:      z.number().nonnegative().optional().default(0),
});

export const listarVagas      = ()        => repo.listarAtivas();
export const listarPorEmpresa = (id: number) => repo.listarPorEmpresa(id);
export const buscarPorId      = (id: number) => repo.buscarPorId(id);
export const desativarVaga    = (id: number) => repo.desativar(id);

export async function criarVaga(dados: unknown): Promise<Partial<Vaga> & { id: number }> {
  const d = schema.parse(dados);
  const id = await repo.criar(d);
  return { id, ...d };
}

export async function atualizarVaga(id: number, dados: unknown): Promise<void> {
  const d = schema.partial().parse(dados);
  await repo.atualizar(id, d as Partial<Vaga>);
}
