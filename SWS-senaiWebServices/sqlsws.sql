create database if not exists `swssenai`;
use `swssenai`;

CREATE TABLE instrutores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    especialidade VARCHAR(100) NOT NULL
);

CREATE TABLE salas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero VARCHAR(20) NOT NULL UNIQUE,
    capacidade INT NOT NULL,
    tipo VARCHAR(50) NOT NULL,  -- Laboratório, Sala de aula, Oficina
    descricao TEXT,
    disponivel BOOLEAN DEFAULT TRUE
);


SELECT * FROM instrutores;
SELECT * FROM salas;