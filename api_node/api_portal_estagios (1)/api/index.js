// Carrega variaveis do .env para process.env
require('dotenv').config();

const express = require('express');
const app = express();

// Middleware: permite que o Express leia corpo JSON nas requisicoes
// Sem isso, req.body seria undefined em POST/PUT
app.use(express.json());

// Importa os arquivos de rotas de cada entidade
const vagaRoutes        = require('./src/routes/vagaRoutes');
const candidaturaRoutes = require('./src/routes/candidaturaRoutes');
const empresaRoutes     = require('./src/routes/empresaRoutes');
const alunoRoutes       = require('./src/routes/alunoRoutes');

// Registra as rotas com prefixo /api
app.use('/api/vagas',        vagaRoutes);
app.use('/api/candidaturas', candidaturaRoutes);
app.use('/api/empresas',     empresaRoutes);
app.use('/api/alunos',       alunoRoutes);

// Rota raiz - apenas para confirmar que a API esta rodando
app.get('/', (req, res) => {
  res.json({ mensagem: 'API Portal de Estágios UniALFA - rodando!' });
});

// Inicia o servidor
const PORT = process.env.PORT || 3000;
app.listen(PORT, () => console.log(`API rodando na porta ${PORT}`));
