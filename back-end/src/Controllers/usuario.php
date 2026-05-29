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
        $this->senhaUsuario = $senhaUsuario;
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

    private function validaSenha($senhaUsuario){
        if (!strlen($senhaUsuario) >= 8 && !strlen($senhaUsuario) <= 20 || !preg_match('/^[0-9A-Za-z\W_ ]+$/', $senhaUsuario)){
            throw new InvalidArgumentException("Senha Inválida, certifique-se que sua senha tenha: \n-Entre 8 a 20 caracteres;\n-Uma letra maiúscula e uma minúscula;\n-Um simbolo especial.")

        }
    }

}