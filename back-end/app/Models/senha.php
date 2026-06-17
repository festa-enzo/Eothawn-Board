<?php

class senha{
    
    private string $senha;

    public function __construct (string $senha){

        $this->validaSenha($senha);
        $this->senha=$senha;
    }

    private function validaSenha($senha){
        if (strlen($senha) < 8 && strlen($senha) > 20 || !preg_match('/^[0-9A-Za-z\W_ ]+$/', $senha)){
            throw new InvalidArgumentException("Senha Inválida, certifique-se que sua senha tenha: \n-Entre 8 a 20 caracteres;\n-Uma letra maiúscula e uma minúscula;\n-Um simbolo especial.")

        }
    }

    public function getValue(): string {
        return $this->senha;
    }
}