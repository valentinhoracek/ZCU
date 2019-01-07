<?php



include_once("settings.inc.php");

class Database{

    private $db;

    public function __construct ()
    {
        global $db_server, $db_name, $db_user, $db_pass;
        // informace se berou ze settings
        $this->db = new PDO(
            "mysql:host=$db_server;dbname=$db_name",
            $db_user,
            $db_pass);


        if(!isset($_SESSION))
        {
            session_start();
        }


        $this->allowCzech();

    }

    private function allowCzech()
    {
        // pro problem s cestinou
        $query = "SET character_set_results = 'utf8',
            character_set_client = 'utf8',
            character_set_connection = 'utf8', 
            character_set_database = 'utf8', 
            character_set_server = 'utf8'";

        $this->execute($query);
    }

    private function execute($dotaz) {
        $res = $this->db->query($dotaz);
        if (!$res) {
            $error = $this->db->errorInfo();
            echo $error[2];
            return null;
        }
        else {
            return $res;
        }
    }


    private function resultObjectToArray($obj) {
        return $obj->fetchAll();
    }

    public function allUserInfo($login) {
        $q = "SELECT * FROM horacekv_users
              WHERE horacekv_users.LOGIN = '$login'";
        $res = $this->execute($q);
        $res = $this->resultObjectToArray($res);

        if ($res != null && count($res) > 0) {
            return $res[0];
        }
        else {
            return null;
        }
    }

    public function allUsersInfo(){
        $q = "SELECT * FROM horacekv_users";
        $res = $this->execute($q);
        $res = $this->resultObjectToArray($res);
        //print_r($res);
        if($res != null && count($res)>0){
            // vracim vse
            return $res;
        } else {
            return null;
        }
    }

    public function isPasswordCorrect($login, $pass){
        $usr = $this->allUserInfo($login);
        if($usr == null){ // uzivatel neni v DB
            return false;
        }
        return $usr['PASSWORD'] == md5($pass); // je heslo stejne?
    }


    public function userLogin($login, $pass){
        if(!$this->isPasswordCorrect($login,$pass)){// neni heslo spatne?
            return false; // spatne heslo
        }
        // ulozim uzivatele do session
        $_SESSION['user'] = $this->allUserInfo($login);
        return true;
    }

    /**
     *  Odhlasi uzivatele.
     */
    public function userLogout(){
        // odstranim session
        //session_unset($_SESSION["user"]);
        session_unset();
    }

    /**
     *  Je uzivatel prihlasen?
     */
    public function isUserLoged(){
        return isset($_SESSION["user"]);
    }

    /**
     *  Vytvori v databazi noveho uzivatele.
     *
     *  @return boolean         Podarilo se uzivatele vytvorit
     */
    public function addNewUser($name, $login, $password, $email){
        $q = "INSERT INTO horacekv_users(ID_USER, FULL_NAME, LOGIN, PASSWORD, EMAIL, ROLE)
              VALUES ('null','$name','$login','$password', '$email', 'AUTHOR')";
        $res = $this->execute($q);
        if($res == null){
            return false;
        } else {
            return true;
        }
    }


    public function allArticleInfo($title)
    {
        $q = "SELECT * FROM horacekv_articles
              WHERE horacekv_articles.TITLE = '$title'";
        $res = $this->execute($q);
        $res = $this->resultObjectToArray($res);

        if ($res != null && count($res) > 0)
        {
            return $res[0];
        }
        else
        {
            return null;
        }
    }

    public function allArticlesInfo()
    {
        $q = "SELECT * FROM horacekv_articles";
        $res = $this->execute($q);
        $res = $this->resultObjectToArray($res);
        //print_r($res);
        if($res != null && count($res)>0){
            // vracim vse
            return $res;
        } else {
            return null;
        }
    }

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
            $q = "INSERT INTO horacekv_articles(ID_ARTICLE, TITLE, FILE_NAME, FILE_DATA, ABSTRACT, STATE, ID_AUTHOR)
              VALUES ('null','$title','$fileName','$fileData','$abstract', 'UNDER REVIEW', '$user')";
            $res = $this->execute($q);
            if($res == null){
                return false;
            } else {
                return true;
            }
        }
        else
        {
            return false;
        }
    }

    public function updateArticleInfo($articleID, $title, $fileName, $fileData, $abstract, $reviews, $state, $authorID)
    {
        $fileData = addslashes($fileData);
        $q = "UPDATE horacekv_articles
                SET TITLE='$title', FILE_NAME='$fileName', FILE_DATA='$fileData', ABSTRACT='$abstract',
                REVIEWS='$reviews', STATE='$state', ID_AUTHOR='$authorID'
                WHERE ID_ARTICLE=$articleID";
        $res = $this->execute($q);
        if($res == null){
            return false;
        } else {
            return true;
        }
    }

    public function deleteReviewsForArticle($articleID)
    {
        $q = "DELETE FROM horacekv_reviews
                WHERE ID_ARTICLE='$articleID'";
        $res = $this->execute($q);
        if($res == null){
            return false;
        } else {
            return true;
        }
    }

    public function deleteArticle($articleID)
    {

        $q = "DELETE FROM horacekv_articles
                WHERE ID_ARTICLE='$articleID'";
        $res = $this->execute($q);
        if($res == null){
            return false;
        } else {
            return true;
        }
    }

    /**
     *  Upravi informace o danem uzivateli.
     *  ... vse potrebne ...
     *  @return boolean         Podarilo se data upravit?
     */
    public function updateUserInfo($userID, $fullName, $email, $login, $password, $role)
    {
        $q = "UPDATE horacekv_users
                SET FULL_NAME='$fullName', LOGIN='$login', PASSWORD='$password', EMAIL='$email', ROLE='$role' 
                WHERE ID_USER=$userID";
        $res = $this->execute($q);
        if($res == null){
            return false;
        } else {
            return true;
        }
    }

    /**
     *  Smaze daneho uzivatele z databaze.
     *  @param integer $userId  ID uzivatele.
     *  @return boolean         Podarilo se?
     */
    public function deleteUser($userID)
    {
        $q = "DELETE FROM horacekv_users
                WHERE ID_USER=$userID";
        $res = $this->execute($q);
        if($res == null){
            return false;
        } else {
            return true;
        }
    }

    public function allReviewsInfo()
    {
        $q = "SELECT * FROM horacekv_reviews";
        $res = $this->execute($q);
        $res = $this->resultObjectToArray($res);
        //print_r($res);
        if($res != null && count($res)>0){
            // vracim vse
            return $res;
        } else {
            return null;
        }
    }

    public function allReviewInfo($reviewID)
    {
        $q = "SELECT * FROM horacekv_reviews
              WHERE horacekv_reviews.ID_REVIEW = '$reviewID'";
        $res = $this->execute($q);
        $res = $this->resultObjectToArray($res);

        if ($res != null && count($res) > 0) {
            return $res[0];
        } else {
            return null;
        }
    }

    public function addNewReview($reviewerID, $articleID){
        $q = "INSERT INTO horacekv_reviews(ID_REVIEW, ID_REVIEWER, ID_ARTICLE)
              VALUES ('null','$reviewerID','$articleID')";
        $res = $this->execute($q);
        if($res == null){
            return false;
        } else {
            return true;
        }
    }

    public function updateReviewInfo($reviewID, $reviewerID, $articleID, $score1, $score2, $score3){
        $q = "UPDATE horacekv_reviews
                SET ID_REVIEWER='$reviewerID', ID_ARTICLE='$articleID', SCORE_1='$score1', SCORE_2='$score2', SCORE_3='$score3' 
                WHERE ID_REVIEW='$reviewID' ";
        $res = $this->execute($q);
        if($res == null){
            return false;
        } else {
            return true;
        }
    }
}

?>