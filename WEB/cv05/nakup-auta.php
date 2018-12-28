<!doctype html>
<?php 
    // načtení souboru s funkcemi
    include("login.class.php");
    $login = new Login;

    // prace s cookies
    include("nakup-auta.class.php");
    $nakup = new NakupAuta;

    if(isset($_POST["potvrzeni"])) {
        if(isset($_POST["action"]) && $_POST["action"]=="uloz") {
            $nakup->createCar($_POST["kola"], $_POST["barva"]);
        }
        else if(isset($_POST["action"]) && $_POST["action"]=="smaz") {
            $nakup->deleteCar();
        }
        header("Refresh: 0");
    }


?>
<html lang="cs">
    <head>
        <meta charset="utf-8">
        <title>Nákup</title>
    </head>
    <body>
        <h1>Osobní stránka - nákup automobilu</h1>
<?php
   ///////////// PRO NEPRIHLASENE UZIVATELE ///////////////

    if(!$login->isUsetLoged()) {
        ?>
        Na tuto stránku mají přístup pouze přihlášení uživatelé.<br>
        Přihlašte se prosím: <a href="login.php">Přihlášení</a>.

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

        Stránka s odhlášením: <a href="login.php">Odhlášení</a>.<br><br>

        <b>Nákup automobilu</b><br>

        <form action="" method="POST">
            <input type="hidden" name="action" value="uloz">
            <table>
                <tr>
                    <td>Pocet kol:</td>
                    <td>
                        <select name="kola">
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="6">6</option>
                            <option value="8">8</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Barva:</td>
                    <td><input type="color" name="barva"></td>
                </tr>
                <tr>
                    <td colspan="2"><input type="submit" name="potvrzeni" value="Uložit"></td>
                </tr>
            </table>
        </form>
        <br>
        Smazat uložené informace:
        <form action="" method="post">
            <input type="hidden" name="action" value="smaz">
            <input type="submit" name="potvrzeni" value="Smazat">
        </form>

        <br><br>
        Vybraný automobil:<br>

        <?php

        if($nakup->isSelectedCar()) {
            echo $nakup->getInfo();
        }
    }
   ///////////// KONEC: PRO PRIHLASENE UZIVATELE ///////////////                
?>
    
    </body>
</html>
             