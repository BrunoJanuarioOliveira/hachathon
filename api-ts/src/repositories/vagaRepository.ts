// src/repositories/vagaRepository.ts
import pool from '../database';
import { Vaga } from '../types';
import { RowDataPacket, ResultSetHeader } from 'mysql2';

export async function listarAtivas(): Promise<Vaga[]> {
  const [rows] = await pool.query<RowDataPacket[]>(`
    SELECT v.*, e.nome AS empresa_nome FROM vagas v
    JOIN empresas e ON e.id = v.empresa_id
    WHERE v.ativa = 1 AND e.status = 'aprovada'
    ORDER BY v.criado_em DESC`);
  return rows as Vaga[];
}

export async function listarPorEmpresa(empresaId: number): Promise<Vaga[]> {
  const [rows] = await pool.query<RowDataPacket[]>(
    'SELECT v.*, e.nome AS empresa_nome FROM vagas v JOIN empresas e ON e.id=v.empresa_id WHERE v.empresa_id=? AND v.ativa=1 ORDER BY v.criado_em DESC',
    [empresaId]);
  return rows as Vaga[];
}

export async function buscarPorId(id: number): Promise<Vaga | null> {
  const [rows] = await pool.query<RowDataPacket[]>(
    'SELECT v.*, e.nome AS empresa_nome FROM vagas v JOIN empresas e ON e.id=v.empresa_id WHERE v.id=?', [id]);
  return (rows[0] as Vaga) || null;
}

export async function criar(d: Omit<Vaga, 'id' | 'ativa' | 'criado_em' | 'empresa_nome'>): Promise<number> {
  const [r] = await pool.query<ResultSetHeader>(
    'INSERT INTO vagas (empresa_id,titulo,descricao,area,bolsa) VALUES(?,?,?,?,?)',
    [d.empresa_id, d.titulo, d.descricao || '', d.area || '', d.bolsa || 0]);
  return r.insertId;
}

export async function atualizar(id: number, d: Partial<Vaga>): Promise<void> {
  await pool.query(
    'UPDATE vagas SET titulo=?,descricao=?,area=?,bolsa=? WHERE id=?',
    [d.titulo, d.descricao || '', d.area || '', d.bolsa || 0, id]);
}

export async function desativar(id: number): Promise<void> {
  await pool.query('UPDATE vagas SET ativa=0 WHERE id=?', [id]);
}
