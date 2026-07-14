<?php

require_once 'back-end/App/Models/nome.php';
require_once 'back-end/App/Models/email.php';
require_once 'back-end/App/Models/senha.php';

class Usuario{

    Private nome $nome;
    Private email $email;
    Private senha $senha;

    public function __construct (nome $nome, email $email, senha $senha ){

        $this->nome = $nome;

        $this->email = $email;

        $this->senha = $senha;
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
    
    public static function createFromRaw(string $nomeRaw, string $emailRaw, string $senhaRaw): self {
        $nome  = new nome($nomeRaw);
        $email = new email($emailRaw);
        $senha = new senha($senhaRaw);   // O Value Object Senha deve fazer o hash

        return new self($nome, $email, $senha);
    }
}
