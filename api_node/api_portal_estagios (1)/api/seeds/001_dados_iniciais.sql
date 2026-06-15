-- Seeds: dados iniciais para teste
USE portal_estagios;

-- Empresas de exemplo
INSERT INTO empresas (nome, cnpj, email, telefone, descricao, status) VALUES
('Tech Solutions Ltda',  '12.345.678/0001-90', 'rh@techsolutions.com',  '(44) 9999-1111', 'Empresa de desenvolvimento de software', 'aprovada'),
('Inovação Digital S.A.', '98.765.432/0001-10', 'contato@inovacao.com.br', '(44) 9999-2222', 'Startup de tecnologia e inovação',        'aprovada'),
('Consultoria ABC',       '11.222.333/0001-44', 'vagas@abc.com',         '(44) 9999-3333', 'Consultoria empresarial',                  'pendente');

-- Alunos de exemplo (senha: 123456)
INSERT INTO alunos (nome, email, senha, curso, periodo) VALUES
('João Silva',    'joao@aluno.unialfa.com.br', '123456', 'Tecnologia em Sistemas para Internet', 3),
('Maria Souza',   'maria@aluno.unialfa.com.br', '123456', 'Tecnologia em Sistemas para Internet', 3),
('Pedro Oliveira', 'pedro@aluno.unialfa.com.br', '123456', 'Análise e Desenvolvimento de Sistemas', 4);

-- Vagas de exemplo
INSERT INTO vagas (empresa_id, titulo, descricao, area, bolsa, ativa) VALUES
(1, 'Estágio em Desenvolvimento Web',   'Trabalhar com React e Node.js', 'Desenvolvimento', 800.00, 1),
(1, 'Estágio em Banco de Dados',        'MySQL e modelagem de dados',    'DBA',             700.00, 1),
(2, 'Estágio em UX/UI Design',          'Figma e prototipagem',          'Design',          750.00, 1);

-- Candidaturas de exemplo
INSERT INTO candidaturas (aluno_id, vaga_id, status) VALUES
(1, 1, 'pendente'),
(2, 1, 'aprovado'),
(1, 3, 'pendente');
