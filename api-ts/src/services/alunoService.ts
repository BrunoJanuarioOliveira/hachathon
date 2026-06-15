// src/services/alunoService.ts
import { z } from 'zod';
import * as repo from '../repositories/alunoRepository';
import { Aluno } from '../types';

const schema = z.object({
  nome:  z.string().min(2),
  ra:    z.string().min(1),
  email: z.string().email(),
});

export const listar      = ()          => repo.listar();
export const buscarPorRa = (ra: string) => repo.buscarPorRa(ra);

export async function criar(dados: unknown): Promise<Partial<Aluno> & { id: number }> {
  const d = schema.parse(dados);
  const id = await repo.criar(d);
  return { id, ...d };
}
