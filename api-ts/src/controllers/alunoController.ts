// src/controllers/alunoController.ts
import { Request, Response } from 'express';
import * as srv from '../services/alunoService';

export async function listar(req: Request, res: Response): Promise<void> {
  try {
    if (req.query.ra) return void res.json(await srv.buscarPorRa(String(req.query.ra)));
    res.json(await srv.listar());
  } catch (e: unknown) {
    res.status(500).json({ erro: (e as Error).message });
  }
}

export async function criar(req: Request, res: Response): Promise<void> {
  try {
    res.status(201).json(await srv.criar(req.body));
  } catch (e: unknown) {
    const err = e as Error;
    res.status(err.name === 'ZodError' ? 422 : 500).json({ erro: err.message });
  }
}
