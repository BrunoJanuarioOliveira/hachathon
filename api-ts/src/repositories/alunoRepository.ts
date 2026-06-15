// src/repositories/alunoRepository.ts
import pool from '../database';
import { Aluno } from '../types';
import { RowDataPacket, ResultSetHeader } from 'mysql2';

export async function listar(): Promise<Aluno[]> {
  const [r] = await pool.query<RowDataPacket[]>('SELECT * FROM alunos ORDER BY nome');
  return r as Aluno[];
}

export async function buscarPorRa(ra: string): Promise<Aluno[]> {
  const [r] = await pool.query<RowDataPacket[]>('SELECT * FROM alunos WHERE ra=?', [ra]);
  return r as Aluno[];
}

export async function criar(d: Pick<Aluno, 'nome' | 'ra' | 'email'>): Promise<number> {
  const [r] = await pool.query<ResultSetHeader>(
    'INSERT INTO alunos(nome,ra,email,senha_hash,apto) VALUES(?,?,?,?,1)',
    [d.nome, d.ra, d.email, 'provisorio']);
  return r.insertId;
}
