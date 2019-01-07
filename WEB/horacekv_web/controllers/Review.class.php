<?php
//Hlavní stránka
class Review extends Controller
{
    public function __construct ()
    {
        $this->view = "review";
        $this->metadata['title'] = "Review - GaCon";
    }

    public function work ($database)
    {
        //$_SESSION['user'] = "";
        //$_SESSION['signed'] = null;

        if (isset($_GET['review']) && isset($_GET['article']))
        {
            // managing other users
            //$_SESSION['managedUser'] = $_GET['manager'];
            $_SESSION['reviewedArticle'] = $database->allArticleInfo($_GET['article']);
            $_SESSION['review'] = $database->allReviewInfo($_GET['review']);
        }

        if (isset($_POST['rate']))
        {
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
                $res = $database->updateReviewInfo(
                    $_SESSION['review']['ID_REVIEW'],
                    $_SESSION['review']['ID_REVIEWER'],
                    $_SESSION['review']['ID_ARTICLE'],
                    $_POST['score1'],
                    $_POST['score2'],
                    $_POST['score3']
                );

                if (!$res)
                {
                    echo "<div class=\"alert alert-secondary\" role=\"alert\">
                    Failed to rate article!</div>";
                }
                else
                {
                    echo "<div class=\"alert alert-light\" role=\"alert\">
                    Article rated!</div>";

                    $this->addReview($database);

                    header('Location: index.php?page=articles');
                }
            }
        }
    }

    public function addReview($database)
    {
        $newReviewCount =  $_SESSION['reviewedArticle']['REVIEWS'] + 1;
        $state = $this->calculateScores($database);

        $this->changeArticleState($database, $newReviewCount, $state);
    }

    public function calculateScores($database)
    {
        //$articles = $database->allArticlesInfo();
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

        if ($reviewCount >= 3)
        {
            $finalScore1 = $score1 / $reviewCount;
            $finalScore2 = $score2 / $reviewCount;
            $finalScore3 = $score3 / $reviewCount;
            echo $finalScore1 . "<br>";
            echo $finalScore2 . "<br>";
            echo $finalScore3 . "<br>";

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

    public function changeArticleState($database, $newReviewCount, $state)
    {
        $res = $database->updateArticleInfo(
            $_SESSION['reviewedArticle']['ID_ARTICLE'],
            $_SESSION['reviewedArticle']['TITLE'],
            $_SESSION['reviewedArticle']['FILE_NAME'],
            $_SESSION['reviewedArticle']['FILE_DATA'],
            $_SESSION['reviewedArticle']['ABSTRACT'],
            $newReviewCount,
            $state,
            $_SESSION['reviewedArticle']['ID_AUTHOR']
        );

        if (!$res)
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