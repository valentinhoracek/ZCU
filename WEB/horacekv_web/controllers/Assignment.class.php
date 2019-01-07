<?php
//Hlavní stránka
class Assignment extends Controller
{

    protected $db;
    public function __construct ()
    {
        $this->view = "assignment";
        $this->metadata['title'] = "Assignment - GaCon";
    }

    public function work ($database)
    {
        //$_SESSION['user'] = "";
        //$_SESSION['signed'] = null;

        $db = $database;
        if (isset($_GET['article']))
        {
            // managing other users
            //$_SESSION['managedUser'] = $_GET['manager'];
            $_SESSION['assignArticle'] = $database->allArticleInfo($_GET['article']);
        }

        $users = $database->allUsersInfo();
        $_SESSION['users'] = $users;

        if (isset($_POST['assign']))
        {
            if (!$_POST['reviewer'])
            {
                echo "<div class=\"alert alert-secondary\" role=\"alert\">
                    You have to select an user!</div>";
            }
            else
            {
                // find id user with his login
                $assignedUser = $database->allUserInfo($_POST['reviewer']);

                $res = $database->addNewReview($assignedUser['ID_USER'], $_SESSION['assignArticle']['ID_ARTICLE']);

                if (!$res)
                {
                    echo "<div class=\"alert alert-secondary\" role=\"alert\">
                    Failed to assign review!</div>";
                }
                else
                {
                    echo "<div class=\"alert alert-light\" role=\"alert\">
                    Article assigned to user: ". $assignedUser['LOGIN'] ."!</div>";
                }
            }
        }
    }

    public function allReviewers()
    {
        //echo $db;
        foreach ($_SESSION['users'] as $u)
        {
            if ($u['ROLE'] != "AUTHOR")
            {
                echo "<option value=\"".$u['LOGIN']."\">".$u['LOGIN']."</option>";
            }
        }
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