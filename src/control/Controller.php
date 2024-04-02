<?php

require_once("model/Animal.php");
require_once("model/AnimalStorage.php");
require_once("model/AnimalBuilder.php");
require_once("view/ViewJSON.php");
require_once("view/View.php");
require_once("Router.php");

class Controller {

    private $view;
    private $animalsDB;
    private $viewJSON;

    function __construct(View $view, AnimalStorage $animalsDB, ViewJSON $viewJSON) {
        $this->view = $view;
        $this->animalsDB = $animalsDB;
        $this->viewJSON = $viewJSON;
    }

    public function showInformation($id) {
        if (key_exists($id, $this->animalsDB->readAll())) {
            $this->view->prepareAnimalPage($this->animalsDB->read($id));
        } else {
            $this->view->prepareUnknownAnimalPage();  
        }
    }

    public function showList(){
        $this->view->prepareListPage($this->animalsDB->readAll());
    }

    public function saveNewAnimal(AnimalBuilder $animalBuilder) {

        $animalData = $animalBuilder->getData();

        $animalData['name'] = htmlspecialchars($animalData['name'], ENT_QUOTES, 'UTF-8');
        $animalData['specie'] = htmlspecialchars($animalData['specie'], ENT_QUOTES, 'UTF-8');

        if(!$animalBuilder->isValid()){
            $error = $animalBuilder->getError();
            $this->view->prepareAnimalCreationPage($animalBuilder);
        }else{
            $newAnimal = $animalBuilder->createAnimal();
            $newID = $this->animalsDB->create($newAnimal);
            $this->view->displayAnimalCreationSuccess($newID);
        }
    }

    public function prepareAnimalJSON($id){
        $check = $this->animalsDB->read($id);
        $tabAnimal = array(
            'nom' => $check->getName(),
            'espece' => $check->getSpecie(),
            'age' => $check->getAge(),
        );
    $this->viewJSON->prepareAnimalJSONPage($tabAnimal);
    }

    //Code Alexis
    public function prepareModifAnimal(AnimalBuilder $animalBuilder, $id) {
        $this->animalsDB->delete($id);
        $animalData = $animalBuilder->getData();

        $animalData['name'] = htmlspecialchars($animalData['name'], ENT_QUOTES, 'UTF-8');
        $animalData['specie'] = htmlspecialchars($animalData['specie'], ENT_QUOTES, 'UTF-8');

        if($animalBuilder->isValid()){
            $newAnimal = $animalBuilder->createAnimal();
            $newID = $this->animalsDB->create($newAnimal);
            $this->view->displayAnimalModifSuccess($newID);
        }
    }

    public function prepareSuppression($id) {
        $this->animalsDB->delete($id);
        $this->view->prepareHomePage();
    }

}

?>
