<?php
//Hlavní stránka
class Main extends Controller
{
    public function __construct ()
    {
        $this->view = "main";
        $this->metadata['title'] = "GaCon";
    }

    public function work ($database)
    {
        //$_SESSION['user'] = "";
        //$_SESSION['signed'] = null;
    }

    public function display ()
    {
        if ($this->view)
        {
            //extract($this->osetri($this->data));
            extract($this->metadata, EXTR_PREFIX_ALL, "");
            //require("views/" . $this->view . ".phtml");
            require ("views/structure.phtml");
        }
    }
}


?>