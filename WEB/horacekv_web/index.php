<?php
include_once("settings.inc.php");

// Nastavení interního kódování pro funkce pro práci s řetězci
mb_internal_encoding("UTF-8");


// Callback pro automatické načítání tříd controllerů a modelů
function load ($class)
{
    // Končí název třídy řetězcem "Controller" ?
    /*if (preg_match('/Controller$/', $class))
        require("controllers/" . $class . ".class.php");
    else
        require("models/" . $class . ".class.php");*/


    if (file_exists( "controllers/" . $class . '.class.php'))
    {
        require("controllers/" . $class . ".class.php");
    }
    elseif (file_exists( "models/" . $class . '.class.php'))
    {
        require("models/" . $class . ".class.php");
    }
    else
    {
        echo "Couldn't find class: " . $class;
    }
}

spl_autoload_register("load");

// Připojení k databázi
//Db::pripoj("127.0.0.1", "root", "", "horacekv_web");

// PDO object of the database
$database = new Database();
//echo $database->isUserLoged();

/*
// Vytvoření routeru a zpracování parametrů od uživatele z URL
$url_controller = new URL_Controller();
$url_controller->selectController(array($_SERVER['REQUEST_URI']));

// Vyrenderování šablony
$url_controller->display();
*/

if (isset($_GET['page'])) {
    //$_GET['page'] = "Controller_".$_GET['page'];
    $controller = new $_GET['page'];
} else {
    $controller = new Main();
}

$controller->work($database);
$controller->display();

?>