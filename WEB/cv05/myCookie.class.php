<?php

class MyCookie{

    private $defExpiration;

    public function __construct() {
        $this->defExpiration = 60 * 60 * 24;
    }

    public function addCookie($name, $value, $expire = null) {
        if(!isset($expire)) {
            $expire = $this->defExpiration;
        }
        setcookie($name, $value,time() + $expire);
    }

    public function readCookie($name) {
        if($this->isCookieSet($name)) {
            return $_COOKIE[$name];
        }
        else {
            return null;
        }
    }

    public function isCookieSet($name) {
        return isset($_COOKIE[$name]);
    }

    public function removeCookie($name) {
        $this->addCookie($name, null, 0);
    }
}

?>