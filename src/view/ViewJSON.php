<?php

require_once('Router.php');

class ViewJSON{

    private $router;
    private $title;
    private $content;
    private $data;

    public function __construct(Router $router,$data){
        $this->router = $router;
        $this->data = $data;
    }

    public function render(){
        $this->title = "";
        $this->content = "";
    ;}

    public function prepareAnimalJSONPage($res){
        header('Content-Type: application/json');
        echo json_encode($res);
    }
}
