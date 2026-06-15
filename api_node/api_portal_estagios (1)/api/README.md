# API RESTful — Portal de Estágios UniALFA
**Hackathon 2026 · Aluno 2 · Node.js**

---

## 🚀 Como rodar o projeto

### 1. Instalar dependências
```bash
cd api/
npm install
```

### 2. Configurar o banco de dados
Execute no MySQL:
```bash
mysql -u root -p < migrations/001_create_tables.sql
mysql -u root -p portal_estagios < seeds/001_dados_iniciais.sql
```

### 3. Configurar o `.env`
Edite o arquivo `.env` com suas credenciais:
```
DB_HOST=localhost
DB_USER=root
DB_PASS=sua_senha_aqui
DB_NAME=portal_estagios
PORT=3000
```

### 4. Iniciar a API
```bash
npm run dev    # desenvolvimento (nodemon)
npm start      # producao
```

---

## 📋 Endpoints disponíveis

### Vagas
| Método | Rota | O que faz |
|--------|------|-----------|
| GET | `/api/vagas` | Lista vagas ativas com nome da empresa |
| POST | `/api/vagas` | Cria nova vaga |
| PUT | `/api/vagas/:id` | Atualiza dados de uma vaga |
| DELETE | `/api/vagas/:id` | Encerra vaga (exclusão lógica) |

### Candidaturas
| Método | Rota | O que faz |
|--------|------|-----------|
| GET | `/api/candidaturas` | Lista candidaturas (filtro: `?aluno_id=X` ou `?vaga_id=Y`) |
| POST | `/api/candidaturas` | Aluno se candidata |
| PATCH | `/api/candidaturas/:id` | Atualiza status da candidatura |

### Empresas
| Método | Rota | O que faz |
|--------|------|-----------|
| GET | `/api/empresas` | Lista empresas |
| POST | `/api/empresas` | Cadastra nova empresa |
| PATCH | `/api/empresas/:id/status` | Aprova ou bloqueia empresa |

### Alunos
| Método | Rota | O que faz |
|--------|------|-----------|
| GET | `/api/alunos` | Lista alunos |
| GET | `/api/alunos/:id` | Busca aluno por ID |
| POST | `/api/alunos` | Cadastra novo aluno |
| PUT | `/api/alunos/:id` | Atualiza dados do aluno |

---

## 🧪 Testando no Insomnia/Postman

**POST /api/vagas**
```json
{
  "empresa_id": 1,
  "titulo": "Estágio em Node.js",
  "descricao": "Desenvolvimento de APIs",
  "area": "Backend",
  "bolsa": 900
}
```

**POST /api/candidaturas**
```json
{
  "aluno_id": 1,
  "vaga_id": 1
}
```

**PATCH /api/candidaturas/1**
```json
{
  "status": "aprovado"
}
```

**POST /api/empresas**
```json
{
  "nome": "Nova Empresa Ltda",
  "cnpj": "00.000.000/0001-00",
  "email": "rh@novaempresa.com",
  "telefone": "(44) 9999-0000"
}
```

**PATCH /api/empresas/1/status**
```json
{
  "status": "aprovada"
}
```

---

## 🏗️ Arquitetura em camadas

```
Requisição HTTP
     │
     ▼
  Routes       → mapeia URL para controller
     │
     ▼
  Controller   → recebe req, devolve res JSON
     │
     ▼
  Service      → aplica regras de negócio + validação Zod
     │
     ▼
  Repository   → executa SQL no banco de dados
     │
     ▼
  MySQL
```
