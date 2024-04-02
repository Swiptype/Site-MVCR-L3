<?php

class Animal{

    private $name;
    private $specie;
    private $age;

    function __construct($name,$specie,$age){
        $this->name = $name;
        $this->specie = $specie;
        $this->age = $age;
    }

    public function getName(){
        return $this->name;
    }

    public function getSpecie(){
        return $this->specie;
    }

    public function getAge(){
        return $this->age;
    }
}