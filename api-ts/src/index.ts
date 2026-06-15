// src/index.ts — Ponto de entrada da API
import 'dotenv/config';
import express, { Request, Response, NextFunction } from 'express';

import vagaRoutes        from './routes/vagaRoutes';
import candidaturaRoutes from './routes/candidaturaRoutes';
import empresaRoutes     from './routes/empresaRoutes';
import alunoRoutes       from './routes/alunoRoutes';
import notificacaoRoutes from './routes/notificacaoRoutes';

const app = express();

// CORS: libera PHP (8080/8081) a chamar a API em 3000
app.use((req: Request, res: Response, next: NextFunction) => {
  res.setHeader('Access-Control-Allow-Origin', '*');
  res.setHeader('Access-Control-Allow-Methods', 'GET,POST,PUT,PATCH,DELETE,OPTIONS');
  res.setHeader('Access-Control-Allow-Headers', 'Content-Type,Authorization');
  if (req.method === 'OPTIONS') return void res.sendStatus(204);
  next();
});

app.use(express.json());

// Rotas com prefixo /api
app.use('/api/vagas',        vagaRoutes);
app.use('/api/candidaturas', candidaturaRoutes);
app.use('/api/empresas',     empresaRoutes);
app.use('/api/alunos',       alunoRoutes);
app.use('/api/notificacoes', notificacaoRoutes);

// Rota raiz
app.get('/', (_req: Request, res: Response) => {
  res.json({ mensagem: 'API Portal UniALFA rodando!', versao: '1.0' });
});

// Handler global de erros
app.use((err: Error, _req: Request, res: Response, _next: NextFunction) => {
  console.error(err);
  res.status(500).json({ erro: err.message });
});

const PORT = process.env.PORT || 3000;
app.listen(PORT, () => console.log(`API rodando em http://localhost:${PORT}`));
