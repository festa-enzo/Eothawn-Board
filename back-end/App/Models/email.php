<?php

class email{
    
    private string $email;

    public function __construct (string $email){

        $this->validaEmail($email);
        $this->email=$email;
    }

    private function validaEmail($emailUsuario){
        if (!filter_var($emailUsuario, FILTER_VALIDATE_EMAIL)){
            throw new InvalidArgumentException("Email inválido. \n");
        }

    }

    public function getValue(): string {
        return $this->email;
    }
}