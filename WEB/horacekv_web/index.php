<?php

include_once("settings.inc.php");

// Set internal coding.
mb_internal_encoding("UTF-8");

/**
 * Callback for controllers and models.
 *
 * @param $class
 */
function load ($class)
{
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

/**
 * Create connection to database.
 */
$database = new Database();

/**
 * Selecting right controller.
 */
if (isset($_GET['page']))
{
    $controller = new $_GET['page'];
}
else
{
    $controller = new Main();
}

/**
 * Main functions of controller.
 */
$controller->work($database);
$controller->display();

?>