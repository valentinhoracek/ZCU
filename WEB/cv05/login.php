<!doctype html>
<?php 
    // načtení souboru s funkcemi
    include("login.class.php");
    $login = new Login;

?>
<html lang="cs">
    <head>
        <meta charset="utf-8">
        <title>Uživatel</title>
    </head>
    <body>
        <h1>Osobní stránka - login</h1>
<?php
   // zpracovani odeslanych formularu

    if(isset($_POST["potvrzeni"])) {
        // pozadavek na login
        if(isset($_POST["action"]) && $_POST["action"]=="login" && isset($_POST["jmeno"]) ) {
            //mam co ulozit
            if($_POST["jmeno"]!="") {
                $login->login($_POST["jmeno"]);
            }
            else {
                echo "Prihlaseni se nezdarilo: nebylo zadano jmeno uzivatele.<br>";
            }
        }
        else if(isset($_POST["action"]) && $_POST["action"]=="logout") {
            $login->logout();
        }
        else {
            echo "Pozor: byl odeslan formular, ale nebyla provedena zadna akce.<br>";
        }
    }
    
   ///////////// PRO NEPRIHLASENE UZIVATELE ///////////////

    if(!$login->isUsetLoged()) {
        ?>
        Přihlášení uživatele:
        <form action="" method="POST">
            <input type="text" name="jmeno">
            <input type="hidden" name="action" value="login">
            <input type="submit" name="potvrzeni" value="Přihlásit">
        </form>

        <?php
        ///////////// KONEC: PRO NEPRIHLASENE UZIVATELE ///////////////

    }
    else {
        ///////////// PRO PRIHLASENE UZIVATELE ///////////////
        ?>
        <b>Přihlášený uživatel</b><br>
        Jméno: <br>
        Datum: <br>
        <br>

        Menu: <a href="nakup-auta.php">Nákup auta</a><br>
        <br>

        Odhlášení uživatele:
        <form action="" method="POST">
            <input type="hidden" name="action" value="logout">
            <input type="submit" name="potvrzeni" value="Odhlásit">
        </form>


        <?php
    }
   ///////////// KONEC: PRO PRIHLASENE UZIVATELE ///////////////                
?>
    
    </body>
</html>
             