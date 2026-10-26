CREATE DATABASE IF NOT EXISTS Ferrorama_db;
USE Ferrorama_db;

CREATE TABLE funcionarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    numero_telefone VARCHAR(15) NOT NULL,
    senha VARCHAR(50) NOT NULL
);

CREATE TABLE gerentes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    numero_telefone VARCHAR(15) NOT NULL,
    senha VARCHAR(50) NOT NULL
);

CREATE TABLE trens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    modelo VARCHAR(100) NOT NULL,
    serie VARCHAR(100) NOT NULL,
    estado ENUM('operante', 'inativo') NOT NULL,
    funcionario_id INT NOT NULL,
    FOREIGN KEY (funcionario_id) REFERENCES funcionarios(id)
);

CREATE TABLE rotas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    distancia DECIMAL(6,2) NOT NULL,
    duracao DECIMAL(6,2) NOT NULL,
    data_criacao DATE NOT NULL,
    trem_id INT NOT NULL,
    FOREIGN KEY (trem_id) REFERENCES trens(id)
);

CREATE TABLE sensores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo VARCHAR(50) NOT NULL,
    nome VARCHAR(50) NOT NULL,
    rota_id INT,
    FOREIGN KEY (rota_id) REFERENCES rotas(id)
);

CREATE TABLE sessoes_persistentes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    tipo_usuario VARCHAR(20) NOT NULL,
    token_hash CHAR(64) NOT NULL UNIQUE,
    expira_em DATETIME NOT NULL,
    INDEX idx_usuario (usuario_id, tipo_usuario)
);

INSERT INTO gerentes
(nome, email, numero_telefone, senha)
VALUES
('Leonardo Aguiar', 'leonardo_aguiar@gerente.detrain.com', '47 98895-8589', 'leonardo.adm'),
('Miguel Kormann', 'miguel_kormann@gerente.detrain.com', '47 99712-0771', 'miguel.adm');

INSERT INTO funcionarios
(nome, email, numero_telefone, senha)
VALUES
('Leonardo Aguiar', 'leonardo_aguiar@usuario.detrain.com', '11 11111-1111', 'leonardo.usuario'),
('Miguel Kormann', 'miguel_kormann@usuario.detrain.com', '11 11111-1112', 'miguel.usuario'),
('Tiago Sbeghen', 'sbeghen@usuario.detrain.com', '11 11111-1113', 'tiago.usuario'),
('Theo Maccari', 'maccari@usuario.detrain.com', '11 11111-1114', 'theo.usuario'),
('Thuany Moretti', 'moretti@usuario.detrain.com', '11 11111-1115', 'thuany.usuario'),
('Arthur Gomes', 'gomes@usuario.detrain.com', '11 11111-1116', 'arthur.usuario'),
('Eduardo Lopes', 'lopes@usuario.detrain.com', '11 11111-1117', 'eduardo.usuario'),
('Julia Mueller', 'mueller@usuario.detrain.com', '11 11111-1118', 'julia.usuario'),
('Luiz Felipe', 'felipe@usuario.detrain.com', '11 11111-1119', 'luiz.usuario'),
('Rafael Tironi', 'tironi@usuario.detrain.com', '11 11111-1110', 'rafael.usuario'),
('Juana Oliveira', 'oliveira@usuario.detrain.com', '11 11111-2111', 'juana.usuario'),
('Nicole Jordan', 'jordan@usuario.detrain.com', '11 11111-3111', 'nicole.usuario'),
('Francisco Goulart', 'goulart@usuario.detrain.com', '11 41111-1111', 'chico.usuario'),
('Davi Zilz', 'zilz@usuario.detrain.com', '11 11171-1111', 'davi.usuario'),
('Hugo Grama', 'grama@usuario.detrain.com', '11 11151-1111', 'hugo.usuario'),
('Guilherme Floriano', 'floriano@usuario.detrain.com', '11 15111-1111', 'guilherme.usuario');

INSERT INTO trens (modelo, serie, estado, funcionario_id)
VALUES
('Thomas', 'TR-001', 'operante', 1),
('Maria Fumaça', 'TR-002', 'operante', 2),
('Expresso Vermelho', 'TR-003', 'operante', 3),
('Locomotiva Azul', 'TR-004', 'inativo', 4),
('Trem de Carga', 'TR-005', 'operante', 5),
('Trem de Passageiros', 'TR-006', 'operante', 6),
('Locomotiva Elétrica', 'TR-007', 'operante', 7),
('Trem Regional', 'TR-008', 'inativo', 8),
('Expresso do Norte', 'TR-009', 'operante', 9),
('Locomotiva Diesel', 'TR-010', 'operante', 10),
('Trem Turístico', 'TR-011', 'operante', 11),
('Trem Intermunicipal', 'TR-012', 'operante', 12),
('Locomotiva Verde', 'TR-013', 'inativo', 13),
('Expresso da Serra', 'TR-014', 'operante', 14),
('Trem Metropolitano', 'TR-015', 'operante', 15);

INSERT INTO rotas
(nome, descricao, distancia, duracao, data_criacao, trem_id)
VALUES
('Itaum', 'Rota ferroviária de Itaum', 12.50, 0.45, '2026-10-26', 1),
('Centro', 'Rota ferroviária do Centro', 8.20, 0.30, '2026-10-26', 2),
('Norte', 'Rota ferroviária da região Norte', 15.00, 0.60, '2026-10-26', 3),
('Sul', 'Rota ferroviária da região Sul', 18.50, 0.75, '2026-10-26', 4),
('Leste', 'Rota ferroviária da região Leste', 10.00, 0.40, '2026-10-26', 5),
('Oeste', 'Rota ferroviária da região Oeste', 14.30, 0.55, '2026-10-26', 6),
('Serra', 'Rota ferroviária da Serra', 25.00, 1.20, '2026-10-26', 7),
('Vale', 'Rota ferroviária do Vale', 20.00, 0.90, '2026-10-26', 8),
('Industrial', 'Rota ferroviária industrial', 11.50, 0.45, '2026-10-26', 9),
('Turística', 'Rota ferroviária turística', 16.00, 0.70, '2026-10-26', 10),
('Intermunicipal', 'Rota ferroviária intermunicipal', 35.00, 1.50, '2026-10-26', 11),
('Litorânea', 'Rota ferroviária litorânea', 28.00, 1.10, '2026-10-26', 12),
('Montanha', 'Rota ferroviária da Montanha', 22.00, 1.00, '2026-10-26', 13),
('Expresso', 'Rota ferroviária expressa', 40.00, 1.30, '2026-10-26', 14),
('Metropolitana', 'Rota ferroviária metropolitana', 30.00, 1.15, '2026-10-26', 15);

INSERT INTO sensores (tipo, nome, rota_id)
VALUES
('Ultrassônico', 'Sensor Itaum', 1),
('Infravermelho', 'Sensor Centro', 2),
('Magnético', 'Sensor Norte', 3),
('Óptico', 'Sensor Sul', 4),
('Ultrassônico', 'Sensor Leste', 5),
('Infravermelho', 'Sensor Oeste', 6),
('Magnético', 'Sensor Serra', 7),
('Óptico', 'Sensor Vale', 8),
('Ultrassônico', 'Sensor Industrial', 9),
('Infravermelho', 'Sensor Turístico', 10),
('Magnético', 'Sensor Intermunicipal', 11),
('Óptico', 'Sensor Litorâneo', 12),
('Ultrassônico', 'Sensor Montanha', 13),
('Infravermelho', 'Sensor Expresso', 14),
('Magnético', 'Sensor Metropolitano', 15);
