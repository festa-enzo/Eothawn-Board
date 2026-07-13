<?php

class nome{
    
    private string $nome;

    public function __construct (string $nome){

        $this->validaNome($nome);
        $this->nome=$nome;
    }

    private function validaNome($nomeUsuario){
        if (!preg_match('/^[A-Za-zÀ-ÿ ]+$/', $nomeUsuario)){
            throw new InvalidArgumentException("Nome inválido, evite acentos e números. \n");
        }

    }

    public function getValue(): string {
        return $this->nome;
    }
}