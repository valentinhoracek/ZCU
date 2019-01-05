<?php

class Registration extends Controller
{
    public function __construct ()
    {
        $this->view = "registration";
        $this->metadata['title'] = "Registration - GeCon";
    }

    public function work ($database)
    {
        if (isset($_POST['signup']))
        {
            if (!$_POST['fullName'])
            {
                echo "<div class=\"alert alert-secondary\" role=\"alert\">
                    You have to fill your name!</div>";
            }
            elseif (!$_POST['email'])
            {
                echo "<div class=\"alert alert-secondary\" role=\"alert\">
                    You have to fill your E-mail address!</div>";
            }
            elseif (!$_POST['login'])
            {
                echo "<div class=\"alert alert-secondary\" role=\"alert\">
                    You have to fill your login!</div>";
            }
            elseif (!$_POST['password'])
            {
                echo "<div class=\"alert alert-secondary\" role=\"alert\">
                    You have to fill your password!</div>";
            }
            elseif ($_POST['password'] != $_POST['passwordCheck'])
            {
                echo "<div class=\"alert alert-secondary\" role=\"alert\">
                    Passwords don't match!</div>";
            }
            else
            {
                if ($database->allUserInfo($_POST['login']) != null)
                {
                    echo "<div class=\"alert alert-secondary\" role=\"alert\">
                    User with this login already exists! Choose different one.</div>";
                }
                else
                {
                    $database->addNewUser(
                        $_POST['fullName'],
                        $_POST['login'],
                        md5($_POST['password']),
                        $_POST['email']);
                    // Login new user automatically
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
            }

        }
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