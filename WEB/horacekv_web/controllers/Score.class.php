<?php

/**
 * Class Score
 *
 * Here you can see all scores for selected article.
 */
class Score extends Controller
{
    public function __construct()
    {
        $this->view = "score";
        $this->metadata['title'] = "Score - GaCon";
    }

    /**
     * Method for showing reviews for selected article.
     */
    public function tableScoreOfMyArticle()
    {
        $database = new Database();
        if (isset($_SESSION['articleReviews'])) {
            foreach ($_SESSION['articleReviews'] as $review) {
                $reviewer = $database->allUserInfoFromID($review['ID_REVIEWER']);

                if ($review['ID_ARTICLE'] == $_SESSION['shownArticle']['ID_ARTICLE']) {
                    echo "
                        <tr>        
                            <td>" . $reviewer['FULL_NAME'] . "</td>
                            <td>" . $review['SCORE_1'] . "</td>
                            <td>" . $review['SCORE_2'] . "</td>
                            <td>" . $review['SCORE_3'] . "</td>
                        </tr>";
                }
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
        if (isset($_GET['rating']))
        {
            $_SESSION['shownArticle'] = $database->allArticleInfo($_GET['rating']);
        }

        //echo $_SESSION['shownArticle']['TITLE'];

        $_SESSION['articleReviews'] = $database->allReviewsInfo();
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