<?php

class Login {

    private $session;
    private $dName = "jmeno";
    private $dDate = "date";


    public function __construct() {
        include_once("mySession.class.php");
        $this->session = new MySession;
    }

    public function isUsetLoged() {
        return $this->session->isSessionSet($this->dName);
    }

    public function login($userName) {
        $this->session->addSession($this->dName, $userName);
        $this->session->addSession($this->dDate, date("d. m. Y, G:m:s"));
    }

    public function logout() {
        $this->session->removeSession($this->dName);
        $this->session->removeSession($this->dDate);
    }

    public function getUserInfo() {
        $name = $this->session->readSession($this->dName);
        $date = $this->session->readSession($this->dDate);

        return "Jmeno: $name<br>Datum: $date<br>";
    }
}

?>