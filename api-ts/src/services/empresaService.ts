// src/services/empresaService.ts
import { z } from 'zod';
import * as repo from '../repositories/empresaRepository';
import { Empresa } from '../types';

const schema = z.object({
  nome:  z.string().min(2),
  cnpj:  z.string().min(14),
  email: z.string().email(),
});

const STATUS_VALIDOS: Empresa['status'][] = ['pendente', 'aprovada', 'bloqueada'];

export const listar         = ()             => repo.listar();
export const buscarPorEmail = (email: string) => repo.buscarPorEmail(email);

export async function criar(dados: unknown): Promise<Partial<Empresa> & { id: number }> {
  const d = schema.parse(dados);
  const id = await repo.criar(d);
  return { id, ...d, status: 'pendente' };
}

export async function alterarStatus(id: number, status: Empresa['status']): Promise<void> {
  if (!STATUS_VALIDOS.includes(status)) throw new Error('Status inválido');
  await repo.alterarStatus(id, status);
}
