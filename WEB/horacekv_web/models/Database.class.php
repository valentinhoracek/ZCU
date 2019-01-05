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
    /**
     *  Upravi informace o danem uzivateli.
     *  ... vse potrebne ...
     *  @return boolean         Podarilo se data upravit?
     */
    public function updateUserInfo($userID, $fullName, $email, $login, $password, $role){
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
    public function deleteUser($userId){
        $q = "DELETE FROM horacev_users
                WHERE ID=$userId";
        $res = $this->execute($q);
        if($res == null){
            return false;
        } else {
            return true;
        }
    }
}

?>