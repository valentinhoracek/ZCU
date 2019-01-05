<?php

class Login extends Controller
{
    public function __construct ()
    {
        $this->view = "login";
        $this->metadata['title'] = "Login - GeCon";
    }

    public function work ($database)
    {
        // Login
        if (isset($_POST['signin']))
        {
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
                $result = $database->userLogin($_POST['login'], $_POST['password']);
                if (!$result)
                {
                    echo "<div class=\"alert alert-secondary\" role=\"alert\">
                    Login failed!</div>";
                }
                else
                {
                    $_SESSION['signed'] = true;
                    $_SESSION['newLogin'] = true;
                    header('Location: index.php?page=main');
                }
            }
        }/*
        if(isset($_REQUEST["action"]) && $_REQUEST["action"]=="login"){
            $res = $database->userLogin($_REQUEST["login"],$_REQUEST["password"]);
            $_SESSION['signed'] = true;
            if(!$res){
                echo "<b>Přihlášení se nezdařilo!<b><br><br>";
                $_SESSION['signed'] = false;
            }
        }*/


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