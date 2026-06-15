-- ============================================================
-- banco.sql — Schema unificado do Portal de Estágios UniALFA
-- Compatível com: api-ts (Node/TypeScript), php (Empresa),
--                 php-aluno (Aluno) e backoffice Java.
-- Execute no MySQL Workbench ou via terminal antes de rodar
-- qualquer módulo da aplicação.
-- ============================================================

CREATE DATABASE IF NOT EXISTS portal_estagios
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE portal_estagios;

-- ----------------------------------------------------------
-- Alunos
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS alunos (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nome        VARCHAR(150) NOT NULL,
    ra          VARCHAR(20)  NOT NULL UNIQUE,
    email       VARCHAR(150) NOT NULL UNIQUE,
    senha_hash  VARCHAR(255) NOT NULL DEFAULT 'provisorio',
    apto        TINYINT(1)   NOT NULL DEFAULT 1,
    criado_em   TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
);

-- ----------------------------------------------------------
-- Empresas  (status ENUM — compatível com api-ts)
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS empresas (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nome        VARCHAR(150) NOT NULL,
    cnpj        VARCHAR(18)  NOT NULL UNIQUE,
    email       VARCHAR(150) NOT NULL UNIQUE,
    senha_hash  VARCHAR(255) NOT NULL DEFAULT 'provisorio',
    status      ENUM('pendente','aprovada','bloqueada') NOT NULL DEFAULT 'pendente',
    criado_em   TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
);

-- ----------------------------------------------------------
-- Vagas
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS vagas (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    empresa_id  INT          NOT NULL,
    titulo      VARCHAR(200) NOT NULL,
    descricao   TEXT,
    area        VARCHAR(100),
    bolsa       DECIMAL(10,2) DEFAULT 0,
    ativa       TINYINT(1)   NOT NULL DEFAULT 1,
    criado_em   TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (empresa_id) REFERENCES empresas(id) ON DELETE CASCADE
);

-- ----------------------------------------------------------
-- Candidaturas  (UNIQUE KEY evita duplicatas)
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS candidaturas (
    id               INT AUTO_INCREMENT PRIMARY KEY,
    aluno_id         INT NOT NULL,
    vaga_id          INT NOT NULL,
    status           ENUM('enviada','em_analise','aprovada','reprovada') NOT NULL DEFAULT 'enviada',
    criado_em        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unica (aluno_id, vaga_id),
    FOREIGN KEY (aluno_id) REFERENCES alunos(id)  ON DELETE CASCADE,
    FOREIGN KEY (vaga_id)  REFERENCES vagas(id)   ON DELETE CASCADE
);

-- ----------------------------------------------------------
-- Notificações  (ausente no banco.sql original — Bug 1)
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS notificacoes (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    aluno_id        INT          NOT NULL,
    candidatura_id  INT          NOT NULL,
    mensagem        VARCHAR(255) NOT NULL,
    lida            TINYINT(1)   NOT NULL DEFAULT 0,
    criado_em       TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (aluno_id)        REFERENCES alunos(id)        ON DELETE CASCADE,
    FOREIGN KEY (candidatura_id)  REFERENCES candidaturas(id)  ON DELETE CASCADE
);

-- ----------------------------------------------------------
-- Dados iniciais de exemplo
-- ----------------------------------------------------------
INSERT IGNORE INTO empresas (nome, cnpj, email, senha_hash, status) VALUES
    ('Tech Solutions Ltda',  '12.345.678/0001-90', 'rh@techsolutions.com',    'provisorio', 'aprovada'),
    ('InfoSistemas SA',      '98.765.432/0001-10', 'vagas@infosist.com',       'provisorio', 'aprovada'),
    ('TechSoft Sistemas',    '11.222.333/0001-44', 'contato@techsoft.com',     'provisorio', 'aprovada'),
    ('StartupXYZ',           '33.444.555/0001-77', 'jobs@startupxyz.com',      'provisorio', 'pendente');

INSERT IGNORE INTO alunos (nome, ra, email, apto) VALUES
    ('Ana Souza',      'TSI2024001', 'ana@aluno.unialfa.edu.br',    1),
    ('Bruno Lima',     'TSI2024002', 'bruno@aluno.unialfa.edu.br',  1),
    ('Carla Mendes',   'TSI2024003', 'carla@aluno.unialfa.edu.br',  0),
    ('Diego Ferreira', 'TSI2024004', 'diego@aluno.unialfa.edu.br',  1),
    ('Joao Silva',     '2024001',    'joao@aluno.unialfa.edu.br',   1);

INSERT IGNORE INTO vagas (empresa_id, titulo, descricao, area, bolsa) VALUES
    (1, 'Estagio em Desenvolvimento Web',  'Trabalho com React e Node.js',        'Desenvolvimento', 900.00),
    (1, 'Estagio em Banco de Dados',       'MySQL e PostgreSQL',                   'Infraestrutura',  800.00),
    (2, 'Estagio em Suporte de TI',        'Atendimento e manutencao',            'Suporte',         750.00),
    (3, 'Estagio em Suporte Tecnico',      'Suporte a usuarios e equipamentos',   'Infraestrutura',  750.00);

INSERT IGNORE INTO candidaturas (aluno_id, vaga_id, status) VALUES
    (1, 1, 'enviada'),
    (2, 1, 'aprovada'),
    (4, 3, 'enviada');

INSERT IGNORE INTO notificacoes (aluno_id, candidatura_id, mensagem) VALUES
    (2, 2, 'Parabens! Sua candidatura para Estagio em Desenvolvimento Web foi aprovada.');
