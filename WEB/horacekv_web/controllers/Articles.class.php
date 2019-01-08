<?php

/**
 * Class Articles
 *
 * Depending on the role of logged user this site shows different types of arrticles.
 */
class Articles extends Controller
{
    public function __construct()
    {
        $this->view = "articles";
        $this->metadata['title'] = "Articles - GeCon";
    }

    /**
     * Method for showing articles where logged user is their author.
     */
    public function tableOfMyArticles()
    {
        $local_path = "horacekv_web/";

        foreach ($_SESSION['articles'] as $a)
        {
            if ($a['ID_AUTHOR'] == $_SESSION['user']['ID_USER'])
            {
                echo "
                <tr>
                    <th scope=\"row\">
                        <button onclick=\"window.location=' " . $local_path . "index.php?page=editor&edited=". $a['TITLE'] ."'\"
                            name=\"article_" . $a['ID_ARTICLE'] . "\" class=\"btn btn-dark col-12\" type=\"button\">
                            " . $a['TITLE'] . "
                        </button>
                    </th>
                    <td>" . $a['FILE_NAME'] . "</td>
                    <td>" . $a['ABSTRACT'] . "</td>
                    <td>" . $a['STATE'] . "</td>          
                    <th scope=\"row\">
                        <button onclick=\"window.location=' " . $local_path . "index.php?page=score&rating=". $a['TITLE'] ."'\"
                            name=\"article_" . $a['ID_ARTICLE'] . "\" class=\"btn btn-dark col-12\" type=\"button\">
                            " . $a['REVIEWS'] . "
                        </button>
                    </th>
                </tr>";
            }
        }
    }

    /**
     * Method for showing articles where logged user is assigned to review them.
     */
    public function tableOfArticlesMyReview()
    {
        $local_path = "horacekv_web/";
        foreach ($_SESSION['articles'] as $a)
        {
            if ($a['STATE'] == "UNDER REVIEW")
            {
                foreach ($_SESSION['reviews'] as $r)
                {
                    if ($_SESSION['user']['ID_USER'] == $r['ID_REVIEWER'] && $r['SCORE_1'] == 0)
                    {
                        if ($a['ID_ARTICLE'] == $r['ID_ARTICLE'])
                        {
                            echo "
                        <tr>
                            <th scope=\"row\">
                                <button onclick=\"window.location=' " . $local_path . "index.php?page=review&review=". $r['ID_REVIEW'] ."&article=". $a['TITLE'] ."'\"
                                    name=\"article_" . $a['ID_ARTICLE'] . "\" class=\"btn btn-dark col-12\" type=\"button\">
                                    " . $a['TITLE'] . "
                                </button>
                            </th>
                            <td>" . $a['FILE_NAME'] . "</td>
                            <td>" . $a['ABSTRACT'] . "</td>
                            <td>" . $a['STATE'] . "</td>
                            <td>" . $a['REVIEWS'] . "</td>
                        </tr>";
                        }
                    }
                }
            }
        }
    }

    /**
     * Method for showing all articles that are still under review.
     */
    public function tableOfArticlesUnderReview()
    {
        $local_path = "horacekv_web/";
        foreach ($_SESSION['articles'] as $a)
        {
            if ($a['STATE'] == "UNDER REVIEW")
            {
                echo "
                <tr>
                    <th scope=\"row\">
                        <button onclick=\"window.location=' " . $local_path . "index.php?page=assignment&article=". $a['TITLE'] ."'\"
                            name=\"article_" . $a['ID_ARTICLE'] . "\" class=\"btn btn-dark col-12\" type=\"button\">
                            " . $a['TITLE'] . "
                        </button>
                    </th>
                    <td>" . $a['FILE_NAME'] . "</td>
                    <td>" . $a['ABSTRACT'] . "</td>
                    <td>" . $a['STATE'] . "</td>
                    <td>" . $a['REVIEWS'] . "</td>
                </tr>";
            }
        }
    }

    /**
     * Method for showing all accepted articles.
     */
    public function tableOfArticlesAccepted()
    {
        foreach ($_SESSION['articles'] as $a)
        {
            if ($a['STATE'] == "ACCEPTED")
            {
                echo "
                <tr>
                    <td>" . $a['TITLE'] . "</td>
                    <td>" . $a['FILE_NAME'] . "</td>
                    <td>" . $a['ABSTRACT'] . "</td>
                    <td>" . $a['REVIEWS'] . "</td>
                    <!--<td>" . $a['STATE'] . "</td>-->
                    
                </tr>";
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
        $articles = $database->allArticlesInfo();
        $_SESSION['articles'] = $articles;

        $reviews = $database->allReviewsInfo();
        $_SESSION['reviews'] = $reviews;

        if (isset($_SESSION['reviews']) )
        {
            $_SESSION['areReviews'] = true;
        }
        else
        {
            $_SESSION['areReviews'] = false;
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