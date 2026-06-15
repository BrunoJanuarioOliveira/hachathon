// src/routes/vagaRoutes.ts
import { Router } from 'express';
import * as c from '../controllers/vagaController';
const router = Router();
router.get('/',       c.listar);
router.get('/:id',    c.buscar);
router.post('/',      c.criar);
router.put('/:id',    c.atualizar);
router.delete('/:id', c.deletar);
export default router;
