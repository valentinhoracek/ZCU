<?php

class Editor extends Controller
{
    protected $f;
    public function __construct ()
    {
        $this->view = "editor";
        $this->metadata['title'] = "Editor - GeCon";
    }

    public function work ($database)
    {
        if (isset($_POST['save']))
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
                            $_SESSION['user'][0]);

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