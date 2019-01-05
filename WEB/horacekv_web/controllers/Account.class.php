<?php

class Account extends Controller
{
    public function __construct ()
    {
        $this->view = "account";
        $this->metadata['title'] = "Account - GeCon";
    }

    public function work ($database)
    {

        if (isset($_GET['manager']))
        {
            // managing other users
            //$_SESSION['managedUser'] = $_GET['manager'];
            $_SESSION['managedUser'] = $database->allUserInfo($_GET['manager']);
        }
        else
        {
            // managing my account
            $_SESSION['managedUser'] = $_SESSION['user'];
        }
        //echo $_GET['manager'];
        //$_SESSION['updatingUser'] = $database->allUserInfo($updatedUser);
        if (isset($_POST['update']))
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
            elseif (!$database->isPasswordCorrect($_SESSION['user']['LOGIN'], $_POST['oldPassword']))
            {
                echo "<div class=\"alert alert-secondary\" role=\"alert\">
                    Your current password is incorrect!</div>";
            }
            elseif ($_POST['password'] != $_POST['passwordCheck'])
            {
                echo "<div class=\"alert alert-secondary\" role=\"alert\">
                    New passwords don't match!</div>";
            }
            else
            {

                if ($_POST['password'] == "")
                {
                    $heslo = $_SESSION['user']['PASSWORD'];
                }
                else
                {
                    $heslo = md5($_POST['password']);
                }

                $database->updateUserInfo(
                    $_SESSION['user']['ID_USER'],
                    $_POST['fullName'],
                    $_POST['email'],
                    $_POST['login'],
                    $heslo,
                    $_SESSION['user']['ROLE']
                );

                $database->userLogin($_SESSION['user']['LOGIN'], $heslo);
                echo "<div class=\"alert alert-light\" role=\"alert\">
                            Account info changed!</div>";
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