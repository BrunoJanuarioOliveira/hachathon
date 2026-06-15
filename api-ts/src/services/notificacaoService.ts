// src/services/notificacaoService.ts
import * as repo from '../repositories/notificacaoRepository';

export const listar     = (id: number) => repo.listarPorAluno(id);
export const marcarLida = (id: number) => repo.marcarLida(id);
