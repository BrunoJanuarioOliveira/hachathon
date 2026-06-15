// src/repositories/empresaRepository.ts
import pool from '../database';
import { Empresa } from '../types';
import { RowDataPacket, ResultSetHeader } from 'mysql2';

export async function listar(): Promise<Empresa[]> {
  const [r] = await pool.query<RowDataPacket[]>('SELECT * FROM empresas ORDER BY nome');
  return r as Empresa[];
}

export async function buscarPorEmail(email: string): Promise<Empresa[]> {
  const [r] = await pool.query<RowDataPacket[]>('SELECT * FROM empresas WHERE email=?', [email]);
  return r as Empresa[];
}

export async function criar(d: Pick<Empresa, 'nome' | 'cnpj' | 'email'>): Promise<number> {
  const [r] = await pool.query<ResultSetHeader>(
    'INSERT INTO empresas(nome,cnpj,email,senha_hash) VALUES(?,?,?,?)',
    [d.nome, d.cnpj, d.email, 'provisorio']);
  return r.insertId;
}

export async function alterarStatus(id: number, status: Empresa['status']): Promise<void> {
  await pool.query('UPDATE empresas SET status=? WHERE id=?', [status, id]);
}
