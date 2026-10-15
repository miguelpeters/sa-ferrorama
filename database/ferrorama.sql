CREATE DATABASE IF NOT EXISTS Ferrorama_db;
USE Ferrorama_db;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    numero_telefone VARCHAR(15) NOT NULL,
    senha VARCHAR(50) NOT NULL
);

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


CREATE TABLE estacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL
);

CREATE TABLE trens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    modelo VARCHAR(100) NOT NULL,
    estacao_id INT,
    sensor_id INT,
    FOREIGN KEY (estacao_id) REFERENCES estacoes(id)
);


CREATE TABLE sensores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    tipo VARCHAR(50) NOT NULL,
    trem_id INT,
    FOREIGN KEY (trem_id) REFERENCES trens(id)
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

INSERT INTO usuarios
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
('Guilherme Floriano', 'floriano@usuario.detrain.com', '11 15111-1111', 'guilherme.usuario'),