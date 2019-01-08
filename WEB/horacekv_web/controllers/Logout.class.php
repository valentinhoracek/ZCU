<?php

/**
 * Class Logout
 */
class Logout extends Controller
{
    public function __construct()
    {
        $this->view = "main";
        $this->metadata['title'] = "Logout - GeCon";
    }

    /**
     * Main method for each controller.
     *
     * @param $database
     * @return mixed
     */
    public function work($database)
    {
        $database->userLogout();
        $_SESSION['signed'] = false;
    }

    /**
     * Method for displaying content of this site.
     *
     * @return mixed
     */
    public function display()
    {
        if ($this->view)
        {
            extract($this->metadata, EXTR_PREFIX_ALL, "");
            require ("views/structure.phtml");
        }
    }
}
?>