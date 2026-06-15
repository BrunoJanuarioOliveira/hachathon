// src/routes/empresaRoutes.ts
import { Router } from 'express';
import * as c from '../controllers/empresaController';
const router = Router();
router.get('/',             c.listar);
router.post('/',            c.criar);
router.patch('/:id/status', c.alterarStatus);
export default router;
