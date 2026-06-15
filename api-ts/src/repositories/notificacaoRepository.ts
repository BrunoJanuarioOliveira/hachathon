// src/repositories/notificacaoRepository.ts
import pool from '../database';
import { Notificacao } from '../types';
import { RowDataPacket } from 'mysql2';

export async function criar(alunoId: number, candidaturaId: number, mensagem: string): Promise<void> {
  await pool.query(
    'INSERT INTO notificacoes (aluno_id,candidatura_id,mensagem) VALUES(?,?,?)',
    [alunoId, candidaturaId, mensagem]);
}

export async function listarPorAluno(alunoId: number): Promise<Notificacao[]> {
  const [rows] = await pool.query<RowDataPacket[]>(`
    SELECT n.*, v.titulo AS titulo_vaga
    FROM notificacoes n
    JOIN candidaturas c ON c.id = n.candidatura_id
    JOIN vagas v ON v.id = c.vaga_id
    WHERE n.aluno_id = ? ORDER BY n.criado_em DESC`, [alunoId]);
  return rows as Notificacao[];
}

export async function marcarLida(id: number): Promise<void> {
  await pool.query('UPDATE notificacoes SET lida=1 WHERE id=?', [id]);
}
