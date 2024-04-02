<?php

require_once('model/Animal.php');
require_once('model/AnimalBuilder.php');
require_once('Router.php');

class View{

    private $router;
    private $title;
    private $content;
    private $feedback;

    function __construct(Router $router,$feedback = null) {
        $this->router = $router;
        $this->feedback = $feedback;
    }
    
    public function render(){
        $title = $this->title;
        $content = $this->content;
        include("squelette.php");
    ;}

    public function prepareTestPage(){
        $this->title = "Le plus beau titre de France";
        $this->content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."
    ;}

    public function prepareAnimalPage(Animal $animal){
        $this->title = $animal->getName();
        $this->content =  "<p>" . $this->title . "->". $animal->getSpecie() . " de " . $animal->getAge() . " ans. </p>\n";
        //Code Alexis
        if (key_exists("id", $_GET)) {
            $this->content .= '<input type="button" value="Modifier" onclick="document.location.href=`'. $this->router->getAnimalModifURL($_GET["id"]) .'`; ">';
            $this->content .= '<input type="button" value="Supprimer" onclick="document.location.href=`'. $this->router->getAnimalSupprURL($_GET["id"]) .'`; ">';
        }   
        
    }

    public function prepareUnknownAnimalPage(){
        $this->title = "Erreur";
        $this->content = "Animal inconnu";
    }
    
    public function prepareHomePage(){
        $this->title = "Accueil";
        $this->content = "Ceci est la page d'accueil";
    }

    public function prepareListPage($animalsTab){
        $this->title = "Liste d'animaux";
        $this->content = "<nav> <ul>";
        foreach ($animalsTab as $id => $animal) {
            $this->content .= "<li><a href=". $this->router->getAnimalURL($id) .">" .$animal->getName() . "</a></li>";
            $this->content.= '<input type="button" value="Détails" onclick="
            let xhr = new XMLHttpRequest();
            let button = event.target;
            let listItem = button.parentNode;
            let realDiv = document.querySelector(\'div\');

            if (button.value === \'Détails\'){
                xhr.open(\'GET\',\'site.php?action=json&id=' . $id . '\',true);
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && xhr.status == 200){
                        let response = JSON.parse(xhr.responseText);

                        let specieEl = document.createElement(\'p\');
                        specieEl.textContent = \'Espece: \' + response.espece;

                        let ageEl = document.createElement(\'p\');
                        ageEl.textContent = \'Age: \' + response.age;

                        let detailsContainer = document.createElement(\'div\');

                        let existingDetails = document.querySelector(\'div\');
                        if (existingDetails){
                            while(existingDetails.firstChild){
                                existingDetails.removeChild(existingDetails.firstChild);
                            }
                        }

                        detailsContainer.appendChild(specieEl);
                        detailsContainer.appendChild(ageEl);

                        listItem.insertAdjacentElement(\'afterend\', detailsContainer);
                        button.value = \'Cacher les détails\';
                    }
                }
            };

            if(button.value === \'Cacher les détails\'){
                realDiv.style.display = \'none\';

                button.value = \'Détails\';
            }

            xhr.send();
            ">';
        }
        $this->content .= "</ul> </nav>";   
    }

    protected function getMenu(){
        return array(
            "Accueil" => $this->router->homePage(),
            "Animaux" => $this->router->allAnimalsPage(),
            "Creation" => $this->router->getAnimalCreationURL(),
        );
    }

    public function prepareDebugPage($variable){
        $this->title = 'Debug';
        $this->content = '<pre>'.htmlspecialchars(var_export($variable,true)).'<pre>';
    }
    
    public function prepareAnimalCreationPage(AnimalBuilder $animalBuilder) {
        $this->title = "Ajoutez votre animal";

        $s = '<form action="'.$this->router->getAnimalSaveURL().'" method="POST">'."\n";
        $s .= "Nom : " . "<input type='text' name='" . AnimalBuilder::NAME_REF . "' required value='" . ($animalBuilder->getData()[AnimalBuilder::NAME_REF] ?? '') . "'>" . "\n";
        $s .= "Espece : " . "<input type='text' name='" . AnimalBuilder::SPECIE_REF . "' required value='" . ($animalBuilder->getData()[AnimalBuilder::SPECIE_REF] ?? '') . "'>" . "\n";
        $s .= "Age : " . "<input type='text' name='" . AnimalBuilder::AGE_REF . "' required value='" . ($animalBuilder->getData()[AnimalBuilder::AGE_REF] ?? '') . "'>" . "\n";

        if ($animalBuilder->getError() !== null){
            $s .= "<p style='color: red;'>Erreur : " . htmlspecialchars($animalBuilder->getError()) . "</p>\n";
        }

        $s .= "<button>Créer</button>\n";
        $s .= "</form>\n";
        $this->content .= $s;
    }
    
    public function displayAnimalCreationSuccess($id){
        $this->router->POSTredirect($this->router->getAnimalURL($id), "Animal créé avec succes !");
    }

    //Code Alexis
    public function prepareAnimalModifPage(Animal $animal, AnimalBuilder $animalBuilder) {
        $this->title = "Modifier votre animal ";
        $id = "";
        if (key_exists("id", $_GET)) {
            $id = $_GET["id"];
        }
        $s = '<form action="'.$this->router->getAnimalSaveModifURL($id).'" method="POST">'."\n";
        $s .= "Nom : " . "<input type='text' name='" . AnimalBuilder::NAME_REF . "' required value='" . $animal->getName() . "'>" . "\n";
        $s .= "Espece : " . "<input type='text' name='" . AnimalBuilder::SPECIE_REF . "' required value='" . $animal->getSpecie() . "'>" . "\n";
        $s .= "Age : " . "<input type='text' name='" . AnimalBuilder::AGE_REF . "' required value='" . $animal->getAge() . "'>" . "\n";

        if ($animalBuilder->getError() !== null){
            $s .= "<p style='color: red;'>Erreur : " . htmlspecialchars($animalBuilder->getError()) . "</p>\n";
        }

        $s .= "<button>Modifier</button>\n";
        $s .= "</form>\n";
        $this->content .= $s;
    }
    
    public function displayAnimalModifSuccess($id){
        $this->router->POSTredirect($this->router->getAnimalURL($id), "Animal modifié avec succes !");
    }

    public function prepareAnimalSupprPage(Animal $animal,$id) {
        $this->title = "Suppression de votre animal";
        $this->content = "<p>Voulez-vous supprimer l'animal (". $animal->getName() .", Espèce: ". $animal->getSpecie() .", Age: ". $animal->getAge() .")</p>\n";
        $this->content .= '<input type="button" value="Oui" onclick="document.location.href=`'. $this->router->getAnimalConfSupprURL($_GET["id"]) .'`; ">';
        $this->content .= '<input type="button" value="Non" onclick="document.location.href=`'. $this->router->getAnimalURL($_GET["id"]) .'`; ">';
    }

    public function displayAnimalSupprSuccess($id){
        $this->router->POSTredirect($this->router->getAnimalURL($id), "Animal supprimé avec succes !");
    }
}