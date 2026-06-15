-- Migration 001: Criacao das tabelas do Portal de Estagios UniALFA
-- Execute este arquivo no MySQL antes de iniciar a API

CREATE DATABASE IF NOT EXISTS portal_estagios CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE portal_estagios;

-- Tabela de empresas
CREATE TABLE IF NOT EXISTS empresas (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  nome       VARCHAR(200)  NOT NULL,
  cnpj       VARCHAR(18)   NOT NULL UNIQUE,
  email      VARCHAR(150)  NOT NULL,
  telefone   VARCHAR(20),
  descricao  TEXT,
  status     ENUM('pendente', 'aprovada', 'bloqueada') NOT NULL DEFAULT 'pendente',
  criado_em  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de alunos
CREATE TABLE IF NOT EXISTS alunos (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  nome       VARCHAR(150)  NOT NULL,
  email      VARCHAR(150)  NOT NULL UNIQUE,
  senha      VARCHAR(255)  NOT NULL,
  curso      VARCHAR(100),
  periodo    TINYINT,
  criado_em  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de vagas
CREATE TABLE IF NOT EXISTS vagas (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  empresa_id  INT NOT NULL,
  titulo      VARCHAR(200)  NOT NULL,
  descricao   TEXT,
  area        VARCHAR(100),
  bolsa       DECIMAL(10,2),
  ativa       TINYINT(1)   NOT NULL DEFAULT 1,
  criado_em   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (empresa_id) REFERENCES empresas(id)
);

-- Tabela de candidaturas
CREATE TABLE IF NOT EXISTS candidaturas (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  aluno_id   INT NOT NULL,
  vaga_id    INT NOT NULL,
  status     ENUM('pendente', 'aprovado', 'reprovado') NOT NULL DEFAULT 'pendente',
  criado_em  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_aluno_vaga (aluno_id, vaga_id),
  FOREIGN KEY (aluno_id) REFERENCES alunos(id),
  FOREIGN KEY (vaga_id)  REFERENCES vagas(id)
);
