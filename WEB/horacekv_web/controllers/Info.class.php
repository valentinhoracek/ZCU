<?php
//Hlavní stránka
class Info extends Controller
{
    public function __construct ()
    {
        $this->view = "info";
        $this->metadata['title'] = "Info - GeCon";
    }

    public function work ($database)
    {
        //$_SESSION['user'] = "";
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