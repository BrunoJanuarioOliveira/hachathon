// src/controllers/vagaController.ts
import { Request, Response } from 'express';
import * as srv from '../services/vagaService';

export async function listar(req: Request, res: Response): Promise<void> {
  try {
    if (req.query.empresa_id)
      return void res.json(await srv.listarPorEmpresa(Number(req.query.empresa_id)));
    res.json(await srv.listarVagas());
  } catch (e: unknown) {
    res.status(500).json({ erro: (e as Error).message });
  }
}

export async function buscar(req: Request, res: Response): Promise<void> {
  try {
    const v = await srv.buscarPorId(Number(req.params.id));
    if (!v) return void res.status(404).json({ erro: 'Vaga não encontrada' });
    res.json(v);
  } catch (e: unknown) {
    res.status(500).json({ erro: (e as Error).message });
  }
}

export async function criar(req: Request, res: Response): Promise<void> {
  try {
    res.status(201).json(await srv.criarVaga(req.body));
  } catch (e: unknown) {
    const err = e as Error;
    res.status(err.name === 'ZodError' ? 422 : 500).json({ erro: err.message });
  }
}

export async function atualizar(req: Request, res: Response): Promise<void> {
  try {
    await srv.atualizarVaga(Number(req.params.id), req.body);
    res.json({ mensagem: 'Vaga atualizada' });
  } catch (e: unknown) {
    const err = e as Error;
    res.status(err.name === 'ZodError' ? 422 : 500).json({ erro: err.message });
  }
}

export async function deletar(req: Request, res: Response): Promise<void> {
  try {
    await srv.desativarVaga(Number(req.params.id));
    res.status(204).send();
  } catch (e: unknown) {
    res.status(500).json({ erro: (e as Error).message });
  }
}
