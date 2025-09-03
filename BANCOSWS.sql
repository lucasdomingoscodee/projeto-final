create database if not exists `swssenai`;
use `swssenai`;


CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user VARCHAR(50) NOT NULL,
    senha VARCHAR(255) NOT NULL,
    tipo VARCHAR(20) NOT NULL
);

-- teste do sql
-- Usuário admin
INSERT INTO usuarios (user, senha, tipo)
VALUES ('lucas', '1234', 'admin');

CREATE TABLE instrutores (
    id_instrutores INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    especialidade VARCHAR(100) NOT NULL
);

CREATE TABLE instrutores (
    id_instrutores INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    especialidade VARCHAR(100) NOT NULL
);

CREATE TABLE salas (
    id_sala INT AUTO_INCREMENT PRIMARY KEY,
    nome_sala VARCHAR(35) NOT NULL,
    descricao TEXT,
    disponivel TINYINT(1) DEFAULT 0
    );

CREATE TABLE aulas (
    id_aula INT AUTO_INCREMENT PRIMARY KEY,
    data DATE NOT NULL,
    materia VARCHAR(100),
    periodo VARCHAR(20),
    id_instrutores INT,
    id_sala INT,
    FOREIGN KEY (id_instrutores) REFERENCES instrutores(id_instrutores),
    FOREIGN KEY (id_sala) REFERENCES salas(id_sala)
);


SELECT * FROM instrutores;
SELECT * FROM salas;
SELECT * FROM usuarios;
SELECT * FROM aulas;
drop table aulas;
DELETE FROM instrutores WHERE id_instrutores in (4,5,6,7,8,9,10);
DELETE FROM instrutores WHERE id_instrutores = 11;
