CREATE TABLE log_convidado (
    codigo_log_convidado CHAR(36) NOT NULL,
    codigo_convidado CHAR(36) NOT NULL,
    codigo_presente CHAR(36) NULL,
    tx_pagina VARCHAR(255) NOT NULL,
    tx_acao VARCHAR(255) NOT NULL,
    dt_registro DATETIME NOT NULL,
    
    PRIMARY KEY(codigo_log_convidado),

    FOREIGN KEY(codigo_convidado) 
    REFERENCES convidado(codigo_convidado)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,

    FOREIGN KEY (codigo_presente)
    REFERENCES presente(codigo_presente)
    ON DELETE NO ACTION 
    ON UPDATE NO ACTION
);