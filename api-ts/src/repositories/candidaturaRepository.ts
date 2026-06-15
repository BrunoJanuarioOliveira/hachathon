// src/repositories/candidaturaRepository.ts
import pool from '../database';
import { Candidatura } from '../types';
import { RowDataPacket, ResultSetHeader } from 'mysql2';

export async function criar(alunoId: number, vagaId: number): Promise<number> {
  const [r] = await pool.query<ResultSetHeader>(
    'INSERT IGNORE INTO candidaturas (aluno_id,vaga_id) VALUES(?,?)', [alunoId, vagaId]);
  if (r.affectedRows === 0) throw new Error('Você já se candidatou a esta vaga');
  return r.insertId;
}

export async function listarPorAluno(alunoId: number): Promise<Candidatura[]> {
  const [rows] = await pool.query<RowDataPacket[]>(`
    SELECT c.*, v.titulo, v.area, v.bolsa, e.nome AS empresa_nome
    FROM candidaturas c
    JOIN vagas v ON v.id = c.vaga_id
    JOIN empresas e ON e.id = v.empresa_id
    WHERE c.aluno_id = ? ORDER BY c.criado_em DESC`, [alunoId]);
  return rows as Candidatura[];
}

export async function listarPorVaga(vagaId: number): Promise<Candidatura[]> {
  const [rows] = await pool.query<RowDataPacket[]>(`
    SELECT c.*, a.nome AS aluno_nome, a.ra, a.email
    FROM candidaturas c
    JOIN alunos a ON a.id = c.aluno_id
    WHERE c.vaga_id = ? ORDER BY c.criado_em DESC`, [vagaId]);
  return rows as Candidatura[];
}

export async function buscarPorId(id: number): Promise<Candidatura | null> {
  const [rows] = await pool.query<RowDataPacket[]>('SELECT * FROM candidaturas WHERE id=?', [id]);
  return (rows[0] as Candidatura) || null;
}

export async function atualizarStatus(id: number, status: Candidatura['status']): Promise<void> {
  await pool.query('UPDATE candidaturas SET status=? WHERE id=?', [status, id]);
}
