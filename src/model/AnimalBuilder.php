<?php

class AnimalBuilder{

    CONST NAME_REF = 'name';
    CONST SPECIE_REF = 'specie';
    CONST AGE_REF = 'age';
    private $data;
    private $error;

    public function __construct($data, $error){
        $this->data = $data;  
        $this->error = null;
    }

    public function getData(){
        return $this->data;
    }

    public function getError(){
        return $this->error;
    }

    public function createAnimal(){
        if($this->isValid()){
            return new Animal($this->data[self::NAME_REF],$this->data[self::SPECIE_REF],$this->data[self::AGE_REF]);
        }
        else{
            return null;
        }
    }

    public function isValid(){
        if($this->data[self::NAME_REF] === null || trim($this->data[self::NAME_REF]) === '' || $this->data[self::SPECIE_REF] === null || trim($this->data[self::SPECIE_REF]) === ''){
            $this->error = "Le nom et l'espece ne peuvent pas être vides";
            return false;
        }
        else if($this->data[self::AGE_REF] === null || $this->data[self::AGE_REF] < 1 || $this->data[self::AGE_REF] > 150){
            $this->error = "L'âge ne doit pas être vide et doit se trouver entre 1 et 150";
            return false;
        }
        else{
            return true;
        }
    }
}