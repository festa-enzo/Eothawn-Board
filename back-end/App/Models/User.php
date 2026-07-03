<?php
class Usuario{

    Private nome $nome;
    Private email $email;
    Private senha $senha;

    public function __construct (nome $nome, email $email, senha $senha ){

        $this->nome = $nome;

        $this->email = $email;

        $this->senha = password_hash($senha, PASSWORD_BCRYPT);
    }

    public function getNome(): string {
        return $this->nome->getValue();
    }

    public function getEmail(): string {
        return $this->email->getValue();
    }

    public function getSenhaHash(): string {
        return $this->senha->getValue();
    }
}
