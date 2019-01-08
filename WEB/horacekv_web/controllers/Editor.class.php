<?php

/**
 * Class Editor
 *
 * On this site you can create new article, or edit and deleted selected one.
 */
class Editor extends Controller
{
    public function __construct()
    {
        $this->view = "editor";
        $this->metadata['title'] = "Editor - GeCon";
    }

    /**
     * Method for creating new article.
     *
     * @param $database
     */
    public function createArticle($database)
    {
        /**
         * Check if inputs are filled.
         */
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
            /**
             * Check if title is unique.
             */
            if ($database->allArticleInfo($_POST['title']) != null)
            {
                echo "<div class=\"alert alert-secondary\" role=\"alert\">
                    Article with this title already exists! Choose different one.</div>";
            }
            else
            {
                /**
                 * Check uploaded file.
                 */
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
                    $result = $database->addNewArticle(
                        $_POST['title'],
                        $_FILES['file'],
                        $_POST['abstract'],
                        $_SESSION['user']['ID_USER']);

                    if (!$result)
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
            }
        }
    }

    /**
     * Method for deleting article and depending reviews.
     *
     * @param $database
     */
    public function deleteArticle($database)
    {
        /**
         * Deleting depending reviews.
         */
        $result = $database->deleteReviewsForArticle($_SESSION['editedArticle']['ID_ARTICLE']);
        if (!$result)
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

        /**
         * Deleting article info.
         */
        $result = $database->deleteArticle($_SESSION['editedArticle']['ID_ARTICLE']);

        if (!$result)
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

    /**
     * Method for updating article information.
     *
     * @param $database
     */
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
            $result = $database->updateArticleInfo(
                $_SESSION['editedArticle']['ID_ARTICLE'],
                $_POST['title'],
                $_SESSION['editedArticle']['FILE_NAME'],
                $_SESSION['editedArticle']['FILE_DATA'],
                $_POST['abstract'],
                $_SESSION['editedArticle']['REVIEWS'],
                $_SESSION['editedArticle']['STATE'],
                $_SESSION['user']['ID_USER']);

            if (!$result)
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

    /**
     * Main method for each controller.
     *
     * @param $database
     * @return mixed
     */
    public function work($database)
    {
        /**
         * Check if editing existing article.
         */
        $_SESSION['editing'] = false;
        if (isset($_GET['edited']))
        {
            $_SESSION['editedArticle'] = $database->allArticleInfo($_GET['edited']);
            $_SESSION['editing'] = true;
        }

        /**
         * Calling function respective to submitted button.
         */
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