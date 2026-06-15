// src/routes/notificacaoRoutes.ts
import { Router } from 'express';
import * as c from '../controllers/notificacaoController';
const router = Router();
router.get('/',      c.listar);
router.patch('/:id', c.marcarLida);
export default router;
