<?php

class AnimalStorageStub implements AnimalStorage {

    private $animalsTab;

    function __construct() {
        $this->animalsTab = array(
            'medor' => new Animal('Medor','chien','8'),
            'felix' => new Animal('Félix', 'chat','3'),
            'denver' => new Animal('Denver', 'dinosaure','35'),
            'mady' => new Animal('Mady', 'chien','7'),
        );
    }

    public function read($id) {
        return $this->animalsTab[$id];
    }

    public function readAll() {
        return $this->animalsTab;
    }

    public function create(Animal $a){
        return null;
    }

    public function delete($id){
        return null;
    }

    public function update($id,Animal $a){
        return null;
    }
}

?>