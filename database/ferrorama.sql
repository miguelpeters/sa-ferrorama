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

INSERT INTO gerentes
(nome, email, numero_telefone, senha)
VALUES
('Leonardo Aguiar', 'leonardo_aguiar@gerente.detrain.com', '47 98895-8589', 'leonardo.adm'),
('Miguel Kormann', 'miguel_kormann@gerente.detrain.com', '47 99712-0771', 'miguel.adm');