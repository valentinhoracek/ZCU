<?php

/**
 * Class Main. Controller for main site.
 */
class Main extends Controller
{
    public function __construct ()
    {
        $this->view = "main";
        $this->metadata['title'] = "GaCon";
    }

    /**
     * Main method for this controller.
     *
     * @param $database
     * @return mixed|void
     */
    public function work ($database)
    {
    }

    /**
     * Method for displaying this site.
     *
     * @return mixed|void
     */
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