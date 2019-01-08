<?php

/**
 * Class Assignment
 *
 * Here can admin select reviewers for given article.
 */
class Assignment extends Controller
{
    public function __construct()
    {
        $this->view = "assignment";
        $this->metadata['title'] = "Assignment - GaCon";
    }

    /**
     * Method for displaying users from which you can select one of them.
     */
    public function allReviewers()
    {
        foreach ($_SESSION['users'] as $u)
        {
            if ($u['ROLE'] != "AUTHOR")
            {
                echo "<option value=\"".$u['LOGIN']."\">".$u['LOGIN']."</option>";
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
        if (isset($_GET['article']))
        {
            $_SESSION['assignArticle'] = $database->allArticleInfo($_GET['article']);
        }

        $users = $database->allUsersInfo();
        $_SESSION['users'] = $users;

        /**
         * If assigment button is submitted.
         */
        if (isset($_POST['assign']))
        {
            if (!$_POST['reviewer'])
            {
                echo "<div class=\"alert alert-secondary\" role=\"alert\">
                    You have to select an user!</div>";
            }
            else
            {
                /**
                 * Get info of selected user.
                 */
                $assignedUser = $database->allUserInfo($_POST['reviewer']);

                /**
                 * Create new review.
                 */
                $result = $database->addNewReview($assignedUser['ID_USER'], $_SESSION['assignArticle']['ID_ARTICLE']);

                if (!$result)
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

    /**
     * Method for displaying content of this site.
     *
     * @return mixed
     */
    public function display()
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