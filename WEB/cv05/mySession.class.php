<?php

class MySession{

    public function __construct() {
        session_start();
    }

    public function addSession($name, $value) {
        $_SESSION[$name] = $value;
    }

    public function readSession($name) {
        if($this->isSessionSet($name)) {
            return $_SESSION[$name];
        }
        else {
            return null;
        }
    }

    public function isSessionSet($name) {
        return isset($_SESSION[$name]);
    }

    public function removeSession($name) {
        unset($_SESSION[$name]);
    }
}


?>