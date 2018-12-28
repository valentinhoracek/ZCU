<?php

class NakupAuta {

    private $cookie;
    private $dWheels = "kola";
    private $dColor = "barva";

    public function __construct() {
        include_once("myCookie.class.php");
        $this->cookie = new MyCookie;
    }

    public function isSelectedCar() {
        return $this->cookie->isCookieSet($this->dWheels);
    }

    public function createCar($wheels, $color) {
        $this->cookie->addCookie($this->dWheels, $wheels);
        $this->cookie->addCookie($this->dColor, $color);
    }

    public function deleteCar() {
        $this->cookie->removeCookie($this->dWheels);
    }

    public function getWheels() {
        return $this->cookie->readCookie($this->dWheels);
    }

    public function getColor() {
        return $this->cookie->readCookie($this->dColor);
    }

    public function getInfo() {
        $str = "";
        for($i = 0; $i < intval($this->getWheels()); $i++) {
            $str .= "<div style='width:50px;height:50px;margin:5px;float:left;background-color:".$this->getColor().";'></div>";
        }
        return $str;
    }

}

?>