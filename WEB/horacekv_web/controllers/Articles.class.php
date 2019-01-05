<?php

class Articles extends Controller
{
    public function __construct ()
    {
        $this->view = "articles";
        $this->metadata['title'] = "Articles - GeCon";
    }

    public function work ($database)
    {
       // $_SESSION['user'] = "";
        //$database = new Database();
        $articles = $database->allArticlesInfo();
        $_SESSION['articles'] = $articles;
    }

    public function tableOfArticles()
    {
        $i = 0;
        foreach ($_SESSION['articles'] as $a)
        {
            $i++;
            if ($a['ID_AUTHOR'] == $_SESSION['user']['ID_USER'])
            {
                echo "
                <tr>
                    <th scope=\"row\">
                        <button name=\"article_" . $a['ID_ARTICLE'] . "\" class=\"btn btn-dark col-lg-2 col-md-3 col-sm-6\" type=\"button\">
                            " . $i . "
                        </button>
                    </th>
                    <td>" . $a['TITLE'] . "</td>
                    <td>" . $a['FILE_NAME'] . "</td>
                    <td>" . $a['ABSTRACT'] . "</td>
                    <td>" . $a['STATE'] . "</td>
                </tr>";
            }
        }
    }

    public function tableOfArticlesUnderReview()
    {
        $i = 0;
        foreach ($_SESSION['articles'] as $a)
        {
            $i++;
            if ($a['STATE'] == "UNDER REVIEW")
            {
                echo "
                <tr>
                    <th scope=\"row\">
                        <button name=\"article_" . $a['ID_ARTICLE'] . "\" class=\"btn btn-dark col-lg-2 col-md-3 col-sm-6\" type=\"button\">
                            " . $i . "
                        </button>
                    </th>
                    <td>" . $a['TITLE'] . "</td>
                    <td>" . $a['FILE_NAME'] . "</td>
                    <td>" . $a['ABSTRACT'] . "</td>
                    <td>" . $a['STATE'] . "</td>
                </tr>";
            }
        }
    }

    public function tableOfArticlesAccepted()
    {
        $i = 0;
        foreach ($_SESSION['articles'] as $a)
        {
            $i++;
            if ($a['STATE'] == "ACCEPTED")
            {
                echo "
                <tr>
                    <th scope=\"row\">
                        <button name=\"article_" . $a['ID_ARTICLE'] . "\" class=\"btn btn-dark col-lg-2 col-md-3 col-sm-6\" type=\"button\">
                            " . $i . "
                        </button>
                    </th>
                    <td>" . $a['TITLE'] . "</td>
                    <td>" . $a['FILE_NAME'] . "</td>
                    <td>" . $a['ABSTRACT'] . "</td>
                    <td>" . $a['STATE'] . "</td>
                </tr>";
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