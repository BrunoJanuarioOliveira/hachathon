// src/controllers/notificacaoController.ts
import { Request, Response } from 'express';
import * as srv from '../services/notificacaoService';

export async function listar(req: Request, res: Response): Promise<void> {
  try {
    if (!req.query.aluno_id)
      return void res.status(400).json({ erro: 'Informe aluno_id' });
    res.json(await srv.listar(Number(req.query.aluno_id)));
  } catch (e: unknown) {
    res.status(500).json({ erro: (e as Error).message });
  }
}

export async function marcarLida(req: Request, res: Response): Promise<void> {
  try {
    await srv.marcarLida(Number(req.params.id));
    res.json({ mensagem: 'Marcada como lida' });
  } catch (e: unknown) {
    res.status(500).json({ erro: (e as Error).message });
  }
}
