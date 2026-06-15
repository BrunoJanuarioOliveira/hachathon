// src/routes/alunoRoutes.ts
import { Router } from 'express';
import * as c from '../controllers/alunoController';
const router = Router();
router.get('/',  c.listar);
router.post('/', c.criar);
export default router;
