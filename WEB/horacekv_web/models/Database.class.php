<?php

include_once("settings.inc.php");

class Database{

    private $db;

    /**
     * Database constructor. Created database connection with info from settings.inc.php.
     */
    public function __construct ()
    {
        global $db_server, $db_name, $db_user, $db_pass;
        $this->db = new PDO(
            "mysql:host=$db_server;dbname=$db_name",
            $db_user,
            $db_pass);

        if (!isset($_SESSION))
        {
            session_start();
        }

        $this->allowCzech();
    }

    /**
     * Method for allowing Czech characters in database.
     */
    private function allowCzech()
    {
        $query = "SET character_set_results = 'utf8',
            character_set_client = 'utf8',
            character_set_connection = 'utf8', 
            character_set_database = 'utf8', 
            character_set_server = 'utf8'";

        $this->execute($query);
    }

    /**
     * Method for executin SQL query.
     *
     * @param $query
     * @return false|PDOStatement|null
     */
    private function execute($query)
    {
        $result = $this->db->query($query);
        if (!$result)
        {
            $error = $this->db->errorInfo();
            echo $error[2];
            return null;
        }
        else {
            return $result;
        }
    }

    /**
     * Method for creating array from query.
     *
     * @param $object
     * @return mixed
     */
    private function resultObjectToArray($object)
    {
        return $object->fetchAll();
    }

    /**
     * Method for fetching user info.
     *
     * @param $login
     * @return |null
     */
    public function allUserInfo($login)
    {
        $query = "SELECT * FROM horacekv_users
              WHERE horacekv_users.LOGIN = '$login'";
        $result = $this->execute($query);
        $result = $this->resultObjectToArray($result);

        if ($result != null && count($result) > 0)
        {
            return $result[0];
        }
        else
        {
            return null;
        }
    }

    /**
     * Method for fetching all users.
     *
     * @return false|mixed|PDOStatement|null
     */
    public function allUsersInfo()
    {
        $query = "SELECT * FROM horacekv_users";
        $result = $this->execute($query);
        $result = $this->resultObjectToArray($result);

        if ($result != null && count($result) > 0)
        {
            return $result;
        }
        else
        {
            return null;
        }
    }

    /**
     * Method for fetching user name.
     *
     * @param $userID
     * @return |null
     */
    public function allUserInfoFromID($userID)
    {
        $query = "SELECT * FROM horacekv_users
              WHERE horacekv_users.ID_USER = '$userID'";
        $result = $this->execute($query);
        $result = $this->resultObjectToArray($result);

        if ($result != null && count($result) > 0)
        {
            return $result[0];
        }
        else
        {
            return null;
        }
    }

    /**
     * Method for checking user's password.
     *
     * @param $login
     * @param $password
     * @return bool
     */
    public function isPasswordCorrect($login, $password)
    {
        $user = $this->allUserInfo($login);
        if ($user == null)
        {
            return false;
        }
        return $user['PASSWORD'] == md5($password);
    }

    /**
     * Merhod for login in user.
     *
     * @param $login
     * @param $pass
     * @return bool
     */
    public function userLogin($login, $password)
    {
        if (!$this->isPasswordCorrect($login, $password))
        {
            return false;
        }
        $_SESSION['user'] = $this->allUserInfo($login);
        return true;
    }

    /**
     * Method for logout.
     */
    public function userLogout()
    {
        session_unset();
    }

    /**
     * Method for adding new user.
     *
     * @param $name
     * @param $login
     * @param $password
     * @param $email
     * @return bool
     */
    public function addNewUser($name, $login, $password, $email)
    {
        $query = "INSERT INTO horacekv_users(ID_USER, FULL_NAME, LOGIN, PASSWORD, EMAIL, ROLE)
              VALUES ('null','$name','$login','$password', '$email', 'AUTHOR')";
        $result = $this->execute($query);
        if ($result == null)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    /**
     * Method for updating user info.
     *
     * @param $userID
     * @param $fullName
     * @param $email
     * @param $login
     * @param $password
     * @param $role
     * @return bool
     */
    public function updateUserInfo($userID, $fullName, $email, $login, $password, $role)
    {
        $query = "UPDATE horacekv_users
                SET FULL_NAME='$fullName', LOGIN='$login', PASSWORD='$password', EMAIL='$email', ROLE='$role' 
                WHERE ID_USER=$userID";
        $result = $this->execute($query);
        if ($result == null){
            return false;
        }
        else
        {
            return true;
        }
    }

    /**
     * Merthod for deleting an user.
     *
     * @param $userID
     * @return bool
     */
    public function deleteUser($userID)
    {
        $query = "DELETE FROM horacekv_users
                WHERE ID_USER=$userID";
        $result = $this->execute($query);
        if ($result == null)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    /**
     * Method for fetching article info.
     *
     * @param $title
     * @return |null
     */
    public function allArticleInfo($title)
    {
        $query = "SELECT * FROM horacekv_articles
              WHERE horacekv_articles.TITLE = '$title'";
        $result = $this->execute($query);
        $result = $this->resultObjectToArray($result);

        if ($result != null && count($result) > 0)
        {
            return $result[0];
        }
        else
        {
            return null;
        }
    }

    /**
     * Method for fetching all articles.
     *
     * @return false|mixed|PDOStatement|null
     */
    public function allArticlesInfo()
    {
        $query = "SELECT * FROM horacekv_articles";
        $result = $this->execute($query);
        $result = $this->resultObjectToArray($result);
        //print_r($res);
        if ($result != null && count($result) > 0)
        {
            // vracim vse
            return $result;
        }
        else
        {
            return null;
        }
    }

    /**
     * Method for adding new article.
     *
     * @param $title
     * @param $fileArray
     * @param $abstract
     * @param $user
     * @return bool
     */
    public function addNewArticle($title, $fileArray, $abstract, $user)
    {
        $folderPath = 'uploads/';

        $fileName = basename($fileArray['name']);
        $newPath = $folderPath . $fileName;

        // Upload file to uploads directory
        if (move_uploaded_file($fileArray['tmp_name'], $newPath))
        {
            // Upload fileData to database
            $file = fopen($newPath, 'r');
            $fileData = fread($file, filesize($newPath));
            $fileData = addslashes($fileData);
            fclose($file);
            $query = "INSERT INTO horacekv_articles(ID_ARTICLE, TITLE, FILE_NAME, FILE_DATA, ABSTRACT, STATE, ID_AUTHOR)
              VALUES ('null','$title','$fileName','$fileData','$abstract', 'UNDER REVIEW', '$user')";
            $result = $this->execute($query);
            if ($result == null)
            {
                return false;
            }
            else
            {
                return true;
            }
        }
        else
        {
            return false;
        }
    }

    /**
     * Method for updating article info.
     *
     * @param $articleID
     * @param $title
     * @param $fileName
     * @param $fileData
     * @param $abstract
     * @param $reviews
     * @param $state
     * @param $authorID
     * @return bool
     */
    public function updateArticleInfo($articleID, $title, $fileName, $fileData, $abstract, $reviews, $state, $authorID)
    {
        $fileData = addslashes($fileData);
        $query = "UPDATE horacekv_articles
                SET TITLE='$title', FILE_NAME='$fileName', FILE_DATA='$fileData', ABSTRACT='$abstract',
                REVIEWS='$reviews', STATE='$state', ID_AUTHOR='$authorID'
                WHERE ID_ARTICLE=$articleID";
        $result = $this->execute($query);
        if ($result == null)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    /**
     * Method for deleting reviews of given article.
     *
     * @param $articleID
     * @return bool
     */
    public function deleteReviewsForArticle($articleID)
    {
        $query = "DELETE FROM horacekv_reviews
                WHERE ID_ARTICLE='$articleID'";
        $result = $this->execute($query);
        if ($result == null)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    /**
     * Method for deleting an article.
     *
     * @param $articleID
     * @return bool
     */
    public function deleteArticle($articleID)
    {
        $query = "DELETE FROM horacekv_articles
                WHERE ID_ARTICLE='$articleID'";
        $result = $this->execute($query);
        if ($result == null)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    /**
     * Method for fetching review info.
     *
     * @param $reviewID
     * @return |null
     */
    public function allReviewInfo($reviewID)
    {
        $query = "SELECT * FROM horacekv_reviews
              WHERE horacekv_reviews.ID_REVIEW = '$reviewID'";
        $result = $this->execute($query);
        $result = $this->resultObjectToArray($result);

        if ($result != null && count($result) > 0)
        {
            return $result[0];
        }
        else
        {
            return null;
        }
    }

    /**
     * Method for fetching all reviews.
     *
     * @return false|mixed|PDOStatement|null
     */
    public function allReviewsInfo()
    {
        $query = "SELECT * FROM horacekv_reviews";
        $result = $this->execute($query);
        $result = $this->resultObjectToArray($result);

        if ($result != null && count($result) > 0)
        {
            return $result;
        }
        else
        {
            return null;
        }
    }

    /**
     * Method for adding new review.
     *
     * @param $reviewerID
     * @param $articleID
     * @return bool
     */
    public function addNewReview($reviewerID, $articleID){
        $query = "INSERT INTO horacekv_reviews(ID_REVIEW, ID_REVIEWER, ID_ARTICLE)
              VALUES ('null','$reviewerID','$articleID')";
        $result = $this->execute($query);
        if ($result == null)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    /**
     * Method for updating a review.
     *
     * @param $reviewID
     * @param $reviewerID
     * @param $articleID
     * @param $score1
     * @param $score2
     * @param $score3
     * @return bool
     */
    public function updateReviewInfo($reviewID, $reviewerID, $articleID, $score1, $score2, $score3){
        $query = "UPDATE horacekv_reviews
                SET ID_REVIEWER='$reviewerID', ID_ARTICLE='$articleID', SCORE_1='$score1', SCORE_2='$score2', SCORE_3='$score3' 
                WHERE ID_REVIEW='$reviewID' ";
        $result = $this->execute($query);
        if ($result == null)
        {
            return false;
        }
        else
        {
            return true;
        }
    }
}

?>