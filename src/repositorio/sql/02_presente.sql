CREATE TABLE presente (
    codigo_presente CHAR(36) NOT NULL,
    codigo_convidado CHAR(36) NULL,
    tx_nome_presente VARCHAR(255) NOT NULL,
    tx_descricao_presente VARCHAR(255) NOT NULL,
    tx_foto_presente VARCHAR(255) NOT NULL,
    dt_registro DATETIME NOT NULL,
    dt_atualizacao DATETIME NULL,
    tx_usuario_registro VARCHAR(255) NOT NULL,
    tx_usuario_atualizacao VARCHAR(255) NULL,
    
    PRIMARY KEY(codigo_presente),

    FOREIGN KEY(codigo_convidado) 
    REFERENCES convidado(codigo_convidado)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
);

ALTER TABLE presente 
ADD dt_desativacao DATETIME NULL; 

ALTER TABLE presente 
ADD tx_usuario_desativacao VARCHAR(255) NULL;