<?php

abstract class Controller
{
    // Název šablony bez přípony
    protected $view;

    protected $metadata = array(
        'title' => "",
        'keywords' => "Game, Conference, Games, Tabletop, PC, VR",
        'description' => "Annual conference about games",
        'local_path' => "horacekv_web/"
    );



    abstract function display();
    abstract function work($database);
}

?>