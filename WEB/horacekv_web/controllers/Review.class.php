<?php

/**
 * Class Review
 *
 * On this site REVIEWER can rate selected article.
 * Each time review is submitted article status is calculated.
 */
class Review extends Controller
{
    public function __construct()
    {
        $this->view = "review";
        $this->metadata['title'] = "Review - GaCon";
    }

    /**
     * Method for updating article information.
     *
     * @param $database
     */
    public function updateArticleState($database)
    {
        /**
         * Increment review count on an article.
         */
        $newReviewCount =  $_SESSION['reviewedArticle']['REVIEWS'] + 1;
        /**
         * Calculate new state of the article.
         */
        $state = $this->calculateState($database);
        /**
         * Update article state.
         */
        $this->changeArticleState($database, $newReviewCount, $state);
    }

    /**
     * Method for calculating new state of the article.
     *
     * @param $database
     * @return string
     */
    public function calculateState($database)
    {
        $reviews = $database->allReviewsInfo();

        $reviewCount = 0;
        $score1 = 0;
        $score2 = 0;
        $score3 = 0;

        foreach ($reviews as $review)
        {
            if ($review['ID_ARTICLE'] == $_SESSION['reviewedArticle']['ID_ARTICLE'])
            {
                if ($review['SCORE_1'] != 0) {
                    $reviewCount++;
                }

                $score1 += $review['SCORE_1'];
                $score2 += $review['SCORE_2'];
                $score3 += $review['SCORE_3'];
            }
        }

        /**
         * Check if there are at least 3 reviews for an article.
         */
        if ($reviewCount >= 3)
        {
            $finalScore1 = $score1 / $reviewCount;
            $finalScore2 = $score2 / $reviewCount;
            $finalScore3 = $score3 / $reviewCount;
            echo $finalScore1 . "<br>";
            echo $finalScore2 . "<br>";
            echo $finalScore3 . "<br>";

            /**
             * Check overall rating of the article.
             */
            if ($finalScore1 >= 2.5 && $finalScore2 >= 2.5 && $finalScore3 >= 2.5)
            {
                return "ACCEPTED";
            }
            else
            {
                return "DECLINED";
            }
        }
        else
        {
            return "UNDER REVIEW";
        }
    }

    /**
     * Method for changing state and review count of the article.
     *
     * @param $database
     * @param $newReviewCount
     * @param $state
     */
    public function changeArticleState($database, $newReviewCount, $state)
    {
        $result = $database->updateArticleInfo(
            $_SESSION['reviewedArticle']['ID_ARTICLE'],
            $_SESSION['reviewedArticle']['TITLE'],
            $_SESSION['reviewedArticle']['FILE_NAME'],
            $_SESSION['reviewedArticle']['FILE_DATA'],
            $_SESSION['reviewedArticle']['ABSTRACT'],
            $newReviewCount,
            $state,
            $_SESSION['reviewedArticle']['ID_AUTHOR']
        );

        if (!$result)
        {
            echo "<div class=\"alert alert-secondary\" role=\"alert\">
                    Failed to update article state!</div>";
        }
        else
        {
            echo "<div class=\"alert alert-light\" role=\"alert\">
                    Article state updated!</div>";
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
         * Get reference to reviewed article and review.
         */
        if (isset($_GET['review']) && isset($_GET['article']))
        {
            $_SESSION['reviewedArticle'] = $database->allArticleInfo($_GET['article']);
            $_SESSION['review'] = $database->allReviewInfo($_GET['review']);
        }

        if (isset($_POST['rate']))
        {
            /**
             * Check if inputs are filled.
             */
            if (!isset($_POST['score1']))
            {
                echo "<div class=\"alert alert-secondary\" role=\"alert\">
                    You have to rate score 1!</div>";
            }
            elseif (!isset($_POST['score2']))
            {
                echo "<div class=\"alert alert-secondary\" role=\"alert\">
                    You have to rate score 2!</div>";
            }
            elseif (!isset($_POST['score3']))
            {
                echo "<div class=\"alert alert-secondary\" role=\"alert\">
                    You have to rate score 3!</div>";
            }
            else
            {
                $result = $database->updateReviewInfo(
                    $_SESSION['review']['ID_REVIEW'],
                    $_SESSION['review']['ID_REVIEWER'],
                    $_SESSION['review']['ID_ARTICLE'],
                    $_POST['score1'],
                    $_POST['score2'],
                    $_POST['score3']
                );

                if (!$result)
                {
                    echo "<div class=\"alert alert-secondary\" role=\"alert\">
                    Failed to rate article!</div>";
                }
                else
                {
                    echo "<div class=\"alert alert-light\" role=\"alert\">
                    Article rated!</div>";

                    /**
                     * Update article information in database.
                     */
                    $this->updateArticleState($database);

                    /**
                     * Send user back to site with articles.
                     */
                    header('Location: index.php?page=articles');
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
            extract($this->metadata, EXTR_PREFIX_ALL, "");
            require ("views/structure.phtml");
        }
    }
}
?>