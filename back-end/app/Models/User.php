<?php
class usuario{

    Private string $nomeUsuario;
    Private string $emailUsuario;
    Private string $senhaUsuario;

    public function __construct (string $nomeUsuario, string $emailUsuario, string $senhaUsuario ){

        $this->validaNome($nomeUsuario);
        $this->nomeUsuario = $nomeUsuario;

        $this->validaEmail($emailUsuario);
        $this->emailUsuario = $emailUsuario;

        $this->validaSenha($senhaUsuario);
        $this->senhaUsuario = password_hash($senha, PASSWORD_DEFAULT)
    }
    
    private function validaNome($nomeUsuario){
        if (!preg_match('/^[A-Za-zÀ-ÿ ]+$/', $nomeUsuario)){
            throw new InvalidArgumentException("Nome inválido, evite acentos e números. \n");
        }

    }

    private function validaEmail($emailUsuario){
        if (!filter_var($emailUsuario, FILTER_VALIDATE_EMAIL)){
            throw new InvalidArgumentException("Email inválido. \n");
        }

    }

    private function validaSenha($senha){
        if (strlen($senha) < 8 && strlen($senha) > 20 || !preg_match('/^[0-9A-Za-z\W_ ]+$/', $senha)){
            throw new InvalidArgumentException("Senha Inválida, certifique-se que sua senha tenha: \n-Entre 8 a 20 caracteres;\n-Uma letra maiúscula e uma minúscula;\n-Um simbolo especial.")

        }
    }
    public function getNome(): string {
        return $this->nome;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getSenhaHash(): string {
        return $this->senhaHash;
    }
}
