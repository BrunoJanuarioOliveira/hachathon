// src/services/candidaturaService.ts
import * as repo      from '../repositories/candidaturaRepository';
import * as notifRepo from '../repositories/notificacaoRepository';
import { Candidatura } from '../types';

const STATUS_VALIDOS: Candidatura['status'][] = ['enviada', 'em_analise', 'aprovada', 'reprovada'];

export async function candidatar(alunoId: number, vagaId: number): Promise<Candidatura> {
  if (!alunoId || !vagaId) throw new Error('aluno_id e vaga_id são obrigatórios');
  const id = await repo.criar(alunoId, vagaId);
  return { id, aluno_id: alunoId, vaga_id: vagaId, status: 'enviada' };
}

export const listarPorAluno = (id: number) => repo.listarPorAluno(id);
export const listarPorVaga  = (id: number) => repo.listarPorVaga(id);

export async function atualizarStatus(id: number, status: Candidatura['status']): Promise<void> {
  if (!STATUS_VALIDOS.includes(status)) throw new Error('Status inválido: ' + status);
  const cand = await repo.buscarPorId(id);
  if (!cand) throw new Error('Candidatura não encontrada');
  await repo.atualizarStatus(id, status);

  // Notificação automática ao mudar status
  const msgs: Partial<Record<Candidatura['status'], string>> = {
    em_analise: 'Sua candidatura está sendo analisada.',
    aprovada:   'Parabéns! Candidatura APROVADA!',
    reprovada:  'Sua candidatura não foi selecionada.',
  };
  const msg = msgs[status];
  if (msg) await notifRepo.criar(cand.aluno_id, id, msg);
}
