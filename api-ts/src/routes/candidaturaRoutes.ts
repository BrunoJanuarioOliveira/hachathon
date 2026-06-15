// src/routes/candidaturaRoutes.ts
import { Router } from 'express';
import * as c from '../controllers/candidaturaController';
const router = Router();
router.get('/',             c.listar);
router.post('/',            c.criar);
router.patch('/:id/status', c.atualizarStatus);
export default router;
