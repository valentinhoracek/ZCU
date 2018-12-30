<?php

include_once("settings.inc.php");

class Database{

    private $db;

    public function __construct() {
        /*$this->db = new PDO("mysql:host='DB_SERVER'; dbname='DB_NAME'",
            DB_USER, DB_PASS );
        session_start();*/



        global $db_server, $db_name, $db_user, $db_pass;
        // informace se berou ze settings
        $this->db = new PDO("mysql:host=$db_server;dbname=$db_name", $db_user, $db_pass);
        session_start();

        $q = "SET character_set_results = 'utf8', character_set_client = 'utf8',
              character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'";

        $this->executeQuery($q);
    }

    private function executeQuery($dotaz) {
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
        $q = "SELECT * FROM horacekv_users, horacekv_rights
              WHERE horacekv_users.LOGIN = '$login'
              AND horacekv_rights.ID = horacekv_users.ID_RIGHTS";
        $res = $this->executeQuery($q);
        $res = $this->resultObjectToArray($res);

        if ($res != null && count($res) > 0) {
            return $res[0];
        }
        else {
            return null;
        }
    }

    public function allUsersInfo(){
        $q = "SELECT * FROM horacekv_users, horacekv_rights
              WHERE horacekv_rights.ID = horacekv_users.ID_RIGHTS";
        $res = $this->executeQuery($q);
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
        return $usr["PASSWORD"] == $pass; // je heslo stejne?
    }


    public function userLogin($login, $pass){
        if(!$this->isPasswordCorrect($login,$pass)){// neni heslo spatne?
            return false; // spatne heslo
        }
        // ulozim uzivatele do session
        $_SESSION["user"] = $this->allUserInfo($login);
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
    public function addNewUser($login,$jmeno, $heslo, $email, $idPrava){
        $q = "INSERT INTO horacekv_users(LOGIN, FULL_NAME, PASSWORD, EMAIL, ID_RIGHTS)
              VALUES ('$login','$jmeno','$heslo','$email',$idPrava)";
        $res = $this->executeQuery($q);
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
    public function updateUserInfo($userId, $jmeno, $heslo, $email, $idPrava){
        $q = "UPDATE horacekv_users
                SET FUL_NAME='$jmeno', PASSWORD='$heslo', EMAIL='$email', ID_RIGHTS=$idPrava 
                WHERE ID=$userId";
        $res = $this->executeQuery($q);
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
        $res = $this->executeQuery($q);
        if($res == null){
            return false;
        } else {
            return true;
        }
    }
}

?>