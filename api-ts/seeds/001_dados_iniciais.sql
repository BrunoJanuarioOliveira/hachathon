USE portal_estagios;
INSERT INTO empresas (nome,cnpj,email,senha_hash,status) VALUES
  ('TechSoft Sistemas','11.222.333/0001-44','contato@techsoft.com','provisorio','aprovada');
INSERT INTO alunos (nome,ra,email,apto) VALUES
  ('Joao Silva','2024001','joao@aluno.unialfa.edu.br',1),
  ('Ana Souza','2024002','ana@aluno.unialfa.edu.br',1);
INSERT INTO vagas (empresa_id,titulo,area,bolsa,descricao) VALUES
  (1,'Estagio em Desenvolvimento Web','Desenvolvimento',900.00,'Atuar no desenvolvimento de sistemas web.'),
  (1,'Estagio em Suporte Tecnico','Infraestrutura',750.00,'Suporte a usuarios e manutencao de equipamentos.');
