<?php

/**
 * Class Controller
 */
abstract class Controller
{
    /**
     * Variable represent view for current controller.
     *
     * @var
     */
    protected $view;

    /**
     * Array represents basic information that changes for most sites.
     *
     * @var array
     */
    protected $metadata = array(
        'title' => "",
        'keywords' => "Game, Conference, Games, Tabletop, PC, VR",
        'description' => "Annual conference about games",
        'local_path' => "horacekv_web/"
    );

    /**
     * Main method for each controller.
     *
     * @param $database
     * @return mixed
     */
    abstract function work($database);

    /**
     * Method for displaying content of this site.
     *
     * @return mixed
     */
    abstract function display();
}
?>