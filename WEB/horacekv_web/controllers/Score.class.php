<?php
//Hlavní stránka
class Score extends Controller
{
    public function __construct ()
    {
        $this->view = "score";
        $this->metadata['title'] = "Score - GaCon";
    }

    public function work ($database)
    {
        //$_SESSION['user'] = "";
        //$_SESSION['signed'] = null;

        if (isset($_GET['rating']))
        {
            // managing other users
            //$_SESSION['managedUser'] = $_GET['manager'];
            $_SESSION['shownArticle'] = $database->allArticleInfo($_GET['rating']);
        }

        //echo $_SESSION['shownArticle']['TITLE'];

        $_SESSION['articleReviews'] = $database->allReviewsInfo();
    }

    public function tableScoreOfMyArticle()
    {


        foreach ($_SESSION['articleReviews'] as $review)
        {
            if ($review['ID_ARTICLE'] == $_SESSION['shownArticle']['ID_ARTICLE'])
            {
                echo "
                        <tr>        
                            <td>" . $_SESSION['shownArticle']['TITLE'] . "</td>
                            <td>" . $review['SCORE_1'] . "</td>
                            <td>" . $review['SCORE_2'] . "</td>
                            <td>" . $review['SCORE_3'] . "</td>
                        </tr>";
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