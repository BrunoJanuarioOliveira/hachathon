-- Execute este script no MySQL Workbench antes de rodar a aplicação Java

CREATE DATABASE IF NOT EXISTS portal_estagios
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE portal_estagios;

-- Tabela de alunos
CREATE TABLE IF NOT EXISTS alunos (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nome        VARCHAR(100) NOT NULL,
    ra          VARCHAR(20)  NOT NULL UNIQUE,
    email       VARCHAR(100),
    senha_hash  VARCHAR(255) NOT NULL DEFAULT 'sem_senha',
    apto        BOOLEAN      NOT NULL DEFAULT TRUE,
    criado_em   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de empresas
CREATE TABLE IF NOT EXISTS empresas (
    id        INT AUTO_INCREMENT PRIMARY KEY,
    nome      VARCHAR(100) NOT NULL,
    cnpj      VARCHAR(18)  UNIQUE,
    email     VARCHAR(100),
    telefone  VARCHAR(20),
    aprovada  BOOLEAN NOT NULL DEFAULT FALSE,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de vagas
CREATE TABLE IF NOT EXISTS vagas (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    empresa_id INT NOT NULL,
    titulo     VARCHAR(100) NOT NULL,
    descricao  TEXT,
    area       VARCHAR(60),
    ativa      BOOLEAN NOT NULL DEFAULT TRUE,
    criado_em  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (empresa_id) REFERENCES empresas(id) ON DELETE CASCADE
);

-- Tabela de candidaturas
CREATE TABLE IF NOT EXISTS candidaturas (
    id                INT AUTO_INCREMENT PRIMARY KEY,
    aluno_id          INT NOT NULL,
    vaga_id           INT NOT NULL,
    data_candidatura  DATE NOT NULL DEFAULT (CURDATE()),
    status            ENUM('pendente','aprovado','reprovado') NOT NULL DEFAULT 'pendente',
    FOREIGN KEY (aluno_id) REFERENCES alunos(id) ON DELETE CASCADE,
    FOREIGN KEY (vaga_id)  REFERENCES vagas(id)  ON DELETE CASCADE
);

-- Dados de exemplo para testar a aplicação
INSERT INTO alunos (nome, ra, email, apto) VALUES
    ('Ana Souza',       'TSI2024001', 'ana@unialfa.edu.br',    TRUE),
    ('Bruno Lima',      'TSI2024002', 'bruno@unialfa.edu.br',  TRUE),
    ('Carla Mendes',    'TSI2024003', 'carla@unialfa.edu.br',  FALSE),
    ('Diego Ferreira',  'TSI2024004', 'diego@unialfa.edu.br',  TRUE);

INSERT INTO empresas (nome, cnpj, email, telefone, aprovada) VALUES
    ('Tech Solutions Ltda',  '12.345.678/0001-90', 'rh@techsolutions.com', '(46)3123-4567', TRUE),
    ('InfoSistemas SA',      '98.765.432/0001-10', 'vagas@infosist.com',   '(46)3987-6543', TRUE),
    ('StartupXYZ',           '11.222.333/0001-44', 'jobs@startupxyz.com',  '(46)99123-4567', FALSE);

INSERT INTO vagas (empresa_id, titulo, descricao, area, ativa) VALUES
    (1, 'Estágio em Desenvolvimento Web',  'Trabalho com React e Node.js', 'Desenvolvimento', TRUE),
    (1, 'Estágio em Banco de Dados',       'MySQL e PostgreSQL',           'Infraestrutura',  TRUE),
    (2, 'Estágio em Suporte de TI',        'Atendimento e manutenção',     'Suporte',         TRUE);

INSERT INTO candidaturas (aluno_id, vaga_id, data_candidatura, status) VALUES
    (1, 1, CURDATE(), 'pendente'),
    (2, 1, CURDATE(), 'aprovado'),
    (4, 3, CURDATE(), 'pendente');
