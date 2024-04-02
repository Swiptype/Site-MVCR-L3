<?php
set_include_path("./src");

require_once("view/View.php");
require_once("view/ViewJSON.php");
require_once("control/Controller.php");
require_once("model/AnimalStorage.php");
require_once("model/AnimalBuilder.php");

class Router{

    public function main(AnimalStorage $animalsDB){

        $feedback = isset($_SESSION['feedback']) ? $_SESSION['feedback'] : null;

        $viewJSON = new ViewJSON($this,null);
        $view = new View($this,$feedback);
        $controller = new Controller($view, $animalsDB,$viewJSON);

        unset($_SESSION['feedback']);

        if (count($_GET) === 1 && key_exists('id', $_GET)){
            $controller->showInformation($_GET["id"]);
            $view->render();
        }
        else if (key_exists('liste', $_GET)){
            $controller->showList();
            $view->render();
        }
        else if (key_exists('action', $_GET)){
            switch ($_GET['action']) {
                case 'nouveau':
                    $animalBuilder = new AnimalBuilder($_POST,null);
                    $view->prepareAnimalCreationPage($animalBuilder);
                    $view->render();
                    break;
                case 'sauverNouveau':
                    $animalBuilder = new AnimalBuilder($_POST,null);
                    $controller->saveNewAnimal($animalBuilder);
                    $view->render();
                    break;
                case 'json':
                    $controller->prepareAnimalJSON($_GET['id']);
                    $viewJSON->render();
                    break;
                //Code Alexis
                case 'modifier':
                    $animalBuilder = new AnimalBuilder($_POST,null);
                    $view->prepareAnimalModifPage($animalsDB->read($_GET['id']), $animalBuilder);
                    $view->render();
                    break;
                case 'sauverModification' :
                    $animalBuilder = new AnimalBuilder($_POST,null);
                    $controller->prepareModifAnimal($animalBuilder, $_GET['id']);
                    $view->render();
                    break;
                case 'supprimer':
                    $view->prepareAnimalSupprPage($animalsDB->read($_GET['id']),$_GET['id']);
                    $view->render();
                    break;
                case 'confirmerSuppression' :
                    $controller->prepareSuppression($_GET['id']);
                    $view->render();
                    break;
            }
        }
        else{
            $view->prepareHomePage();
            $view->render();
        }
    }

    public function getAnimalURL($id) {
        return "site.php?id=" . $id;
    }

    public function homePage(){
        return "site.php";
    }

    public function allAnimalsPage(){
        return "site.php?liste";
    }

    public function getAnimalCreationURL(){
        return "site.php?action=nouveau";
    }

    public function getAnimalSaveURL(){
        return "site.php?action=sauverNouveau";
    }

    public function POSTredirect($url,$feedback){
        $_SESSION['feedback'] = $feedback;
        //header("HTTP/1.1 303 See Other");
        header("Location: $url");
        exit();
    }

    //Code Alexis
    public function getAnimalModifURL($id){
        return "site.php?action=modifier&id=" . $id;
    }

    public function getAnimalSaveModifURL($id){
        return "site.php?action=sauverModification&id=" . $id;
    }

    public function getAnimalSupprURL($id) {
        return "site.php?action=supprimer&id=" . $id;
    }

    public function getAnimalConfSupprURL($id){
        return "site.php?action=confirmerSuppression&id=" . $id;
    }
    
}
