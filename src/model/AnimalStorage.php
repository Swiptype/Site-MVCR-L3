<?php

interface AnimalStorage {

    public function read($id);

    public function readAll();

    /*
     ajoute à la base l'animal donné en argument, 
     et retourne l'identifiant de l'animal ainsi créé
    */
    public function create(Animal $a);

    /*
    supprime de la base l'animal correspondant à l'identifiant 
    donné en argument ; retourne true si la suppression a 
    été effectuée et false si l'identifiant ne correspond 
    à aucun animal.
    */
    public function delete($id);

    /*
    met à jour dans la base l'animal d'identifiant donné, 
    en le remplaçant par l'animal donné ; 
    retourne true si la mise à jour a bien été effectuée, 
    et false si l'identifiant n'existe pas 
    (et donc rien n'a été modifié)
    */
    public function update($id,Animal $a);

}

?>