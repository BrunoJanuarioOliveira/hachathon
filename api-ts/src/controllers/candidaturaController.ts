// src/controllers/candidaturaController.ts
import { Request, Response } from 'express';
import * as srv from '../services/candidaturaService';
import { Candidatura } from '../types';

export async function listar(req: Request, res: Response): Promise<void> {
  try {
    const { aluno_id, vaga_id } = req.query;
    if (aluno_id) return void res.json(await srv.listarPorAluno(Number(aluno_id)));
    if (vaga_id)  return void res.json(await srv.listarPorVaga(Number(vaga_id)));
    res.status(400).json({ erro: 'Informe aluno_id ou vaga_id' });
  } catch (e: unknown) {
    res.status(500).json({ erro: (e as Error).message });
  }
}

export async function criar(req: Request, res: Response): Promise<void> {
  try {
    res.status(201).json(await srv.candidatar(Number(req.body.aluno_id), Number(req.body.vaga_id)));
  } catch (e: unknown) {
    res.status(400).json({ erro: (e as Error).message });
  }
}

export async function atualizarStatus(req: Request, res: Response): Promise<void> {
  try {
    await srv.atualizarStatus(Number(req.params.id), req.body.status as Candidatura['status']);
    res.json({ mensagem: 'Status atualizado' });
  } catch (e: unknown) {
    res.status(400).json({ erro: (e as Error).message });
  }
}
