<?php

class Logout extends Controller
{
    public function __construct ()
    {
        $this->view = "main";
        $this->metadata['title'] = "Logout - GeCon";
    }

    public function work ($database)
    {
        //$_SESSION['user'] = "";
        $database->userLogout();
        $_SESSION['signed'] = false;
    }

    public function display ()
    {
        if ($this->view)
        {
            extract($this->metadata, EXTR_PREFIX_ALL, "");
            require ("views/structure.phtml");
        }
    }
}

?>