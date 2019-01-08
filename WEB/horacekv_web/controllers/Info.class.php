<?php

/**
 * Class Info
 *
 */
class Info extends Controller
{
    public function __construct()
    {
        $this->view = "info";
        $this->metadata['title'] = "Info - GeCon";
    }

    /**
     * Main method for each controller.
     *
     * @param $database
     * @return mixed
     */
    public function work($database)
    {
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