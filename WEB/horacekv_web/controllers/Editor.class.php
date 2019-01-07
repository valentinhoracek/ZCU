<?php

class Editor extends Controller
{
   // protected $f;
    //protected $editing = false;

    public function __construct ()
    {
        $this->view = "editor";
        $this->metadata['title'] = "Editor - GeCon";
    }

    public function createArticle($database)
    {
        if (!$_POST['title'])
        {
            echo "<div class=\"alert alert-secondary\" role=\"alert\">
                    You have to fill the title!</div>";
        }
        elseif ($_FILES['file']['size'] == 0)
        {
            echo "<div class=\"alert alert-secondary\" role=\"alert\">
                    You have to submit PDF file!</div>";
        }
        elseif (!$_POST['abstract'])
        {
            echo "<div class=\"alert alert-secondary\" role=\"alert\">
                    You have to fill abstract for article!</div>";
        }
        else
        {
            if ($database->allArticleInfo($_POST['abstract']) != null)
            {
                echo "<div class=\"alert alert-secondary\" role=\"alert\">
                    Article with this title already exists! Choose different one.</div>";
            }
            else
            {
                if($_FILES['file']['type'] != "application/pdf")
                {
                    echo "<div class=\"alert alert-secondary\" role=\"alert\">
                            Article has wrong type! Please submit only PDF documents.</div>";
                }
                elseif ($_FILES['file']['size'] > 7 * MB)
                {
                    echo "<div class=\"alert alert-secondary\" role=\"alert\">
                            File is too big! Please choose a smaller one.</div>";
                }
                else
                {
                    $res = $database->addNewArticle(
                        $_POST['title'],
                        $_FILES['file'],
                        $_POST['abstract'],
                        $_SESSION['user']['ID_USER']);

                    if (!$res)
                    {
                        echo "<div class=\"alert alert-secondary\" role=\"alert\">
                            Failed to upload article!</div>";
                    }
                    else
                    {
                        echo "<div class=\"alert alert-light\" role=\"alert\">
                            Article uploaded!</div>";
                    }
                }
                //echo $_FILES['file']['tmp_name'];
            }
        }
    }

    public function deleteArticle($database)
    {
        $res = $database->deleteReviewsForArticle($_SESSION['editedArticle']['ID_ARTICLE']);
        if (!$res)
        {
            echo "<div class=\"alert alert-secondary\" role=\"alert\">
                    Failed to delete reviews for article!</div>";
        }
        else
        {
            $_SESSION['editing'] = false;
            echo "<div class=\"alert alert-light\" role=\"alert\">
                    Reviews for article deleted!</div>";
        }

        $res = $database->deleteArticle($_SESSION['editedArticle']['ID_ARTICLE']);

        if (!$res)
        {
            echo "<div class=\"alert alert-secondary\" role=\"alert\">
                    Failed to delete article info!</div>";
        }
        else
        {
            $_SESSION['editing'] = false;
            echo "<div class=\"alert alert-light\" role=\"alert\">
                    Article deleted!</div>";
        }
    }

    public function updateArticle($database)
    {
        if (!$_POST['title'])
        {
            echo "<div class=\"alert alert-secondary\" role=\"alert\">
                    You have to fill the title!</div>";
        }
        elseif (!$_POST['abstract'])
        {
            echo "<div class=\"alert alert-secondary\" role=\"alert\">
                    You have to fill abstract for article!</div>";
        }
        else
        {
            $res = $database->updateArticleInfo(
                $_SESSION['editedArticle']['ID_ARTICLE'],
                $_POST['title'],
                $_SESSION['editedArticle']['FILE_NAME'],
                $_SESSION['editedArticle']['FILE_DATA'],
                $_POST['abstract'],
                $_SESSION['editedArticle']['REVIEWS'],
                $_SESSION['editedArticle']['STATE'],
                $_SESSION['user']['ID_USER']);

            if (!$res)
            {
                echo "<div class=\"alert alert-secondary\" role=\"alert\">
                            Failed to update article!</div>";
            }
            else
            {
                echo "<div class=\"alert alert-light\" role=\"alert\">
                            Article updated!</div>";
                $_SESSION['editedArticle'] = $database->allArticleInfo($_POST['title']);
            }
        }
    }

    public function work ($database)
    {

        $_SESSION['editing'] = false;
        if (isset($_GET['edited']))
        {
            // managing other users
            //$_SESSION['managedUser'] = $_GET['manager'];
            $_SESSION['editedArticle'] = $database->allArticleInfo($_GET['edited']);
            $_SESSION['editing'] = true;
        }

        if (isset($_POST['create']))
        {
            $this->createArticle($database);
        }
        elseif (isset($_POST['delete']))
        {
            $this->deleteArticle($database);
        }
        elseif (isset($_POST['update']))
        {
            $this->updateArticle($database);
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