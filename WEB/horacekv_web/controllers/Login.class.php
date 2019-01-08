<?php

/**
 * Class Login
 *
 * Here you can login into the site.
 */
class Login extends Controller
{
    public function __construct()
    {
        $this->view = "login";
        $this->metadata['title'] = "Login - GeCon";
    }

    /**
     * Main method for each controller.
     *
     * @param $database
     * @return mixed
     */
    public function work($database)
    {
        if (isset($_POST['signin']))
        {
            /**
             * Check if inputs are filled.
             */
            if (!$_POST['login'])
            {
                echo "<div class=\"alert alert-secondary\" role=\"alert\">
                    You have to fill your login!</div>";
            }
            elseif (!$_POST['password'])
            {
                echo "<div class=\"alert alert-secondary\" role=\"alert\">
                    You have to fill your password!</div>";
            }
            else
            {
                /**
                 * Login user.
                 */
                $result = $database->userLogin($_POST['login'], $_POST['password']);
                if (!$result)
                {
                    echo "<div class=\"alert alert-secondary\" role=\"alert\">
                    Login failed!</div>";
                }
                else
                {
                    /**
                     * Go to the main site.
                     */
                    $_SESSION['signed'] = true;
                    $_SESSION['newLogin'] = true;
                    header('Location: index.php?page=main');
                }
            }
        }
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