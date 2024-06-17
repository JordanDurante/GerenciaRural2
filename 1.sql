CREATE TABLE login (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    login VARCHAR(50) NOT NULL,
    senha VARCHAR(50) NOT NULL,
    id_propriedade INT,
    FOREIGN KEY (id_propriedade) REFERENCES propriedade(id)
);

CREATE TABLE propriedade (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL
);

CREATE TABLE cultura (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL
);

CREATE TABLE propriedade_cultura (
    id_propriedade INT,
    id_cultura INT,
    PRIMARY KEY (id_propriedade, id_cultura),
    FOREIGN KEY (id_propriedade) REFERENCES propriedade(id),
    FOREIGN KEY (id_cultura) REFERENCES cultura(id)
);

CREATE TABLE lancamento_financeiro (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo ENUM('entrada', 'saida') NOT NULL,
    data DATE NOT NULL,
    valor DECIMAL(10, 2) NOT NULL,
    quantidade DECIMAL(10, 2) NOT NULL,
    unidade VARCHAR(50) NOT NULL,
    id_cultura INT,
    id_propriedade INT,
    FOREIGN KEY (id_cultura) REFERENCES cultura(id),
    FOREIGN KEY (id_propriedade) REFERENCES propriedade(id)
);

CREATE TABLE unidade (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    descricao TEXT
);
