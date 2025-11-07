CREATE TABLE convidado(
    codigo_convidado CHAR(36) NOT NULL,
    tx_nome_convidado VARCHAR(255) NOT NULL,
    tx_telefone_convidado VARCHAR(50) NULL,
    bl_confirmacao TINYINT(1) NULL, 
    bl_mensagem_enviada TINYINT(1) NOT NULL DEFAULT 0,
    nu_qtd_pessoas INT NULL,
    dt_registro DATETIME NOT NULL,
    dt_atualizacao DATETIME NULL,
    tx_usuario_registro VARCHAR(255) NOT NULL,
    tx_usuario_atualizacao VARCHAR(255) NULL,
    
    PRIMARY KEY(codigo_convidado)
);