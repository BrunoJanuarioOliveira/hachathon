// src/types.ts — Interfaces de domínio do sistema

export interface Aluno {
  id: number;
  nome: string;
  ra: string;
  email: string;
  apto: boolean;
  criado_em?: Date;
}

export interface Empresa {
  id: number;
  nome: string;
  cnpj: string;
  email: string;
  status: 'pendente' | 'aprovada' | 'bloqueada';
  criado_em?: Date;
}

export interface Vaga {
  id: number;
  empresa_id: number;
  empresa_nome?: string;
  titulo: string;
  descricao: string;
  area: string;
  bolsa: number;
  ativa: boolean;
  criado_em?: Date;
}

export interface Candidatura {
  id: number;
  aluno_id: number;
  vaga_id: number;
  status: 'enviada' | 'em_analise' | 'aprovada' | 'reprovada';
  criado_em?: Date;
  // campos extras via JOIN
  titulo?: string;
  area?: string;
  bolsa?: number;
  empresa_nome?: string;
  aluno_nome?: string;
  ra?: string;
  email?: string;
}

export interface Notificacao {
  id: number;
  aluno_id: number;
  candidatura_id: number;
  mensagem: string;
  lida: boolean;
  titulo_vaga?: string;
  criado_em?: Date;
}
