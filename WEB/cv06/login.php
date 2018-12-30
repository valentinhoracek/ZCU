<?php 
    // nacteni hlavicky stranky
    include("zaklad.php");
    head("Přihlášení a odhlášení uživatele");
?>

<?php 
    // načtení souboru s funkcemi
    include("database.class.php");
    $PDOObj = new Database();

?>

<?php

    //echo $_REQUEST["login"]."<br>";
    //echo $_REQUEST["heslo"]."<br>";

    //echo $PDOObj->allUsersInfo();
   // zpracovani odeslanych formularu
    // odhlaseni uzivatele
    if(isset($_REQUEST["action"]) && $_REQUEST["action"]=="logout"){
        $PDOObj->userLogout();
    }
    // prihlaseni uzivatele
    if(isset($_REQUEST["action"]) && $_REQUEST["action"]=="login"){
        $res = $PDOObj->userLogin($_REQUEST["login"],$_REQUEST["heslo"]);
        if(!$res){
            echo "<b>Přihlášení se nezdařilo!<b><br><br>";
        }
    }

    // je uzivatel aktualne prihlasen
    if(!$PDOObj->isUserLoged()) { // neni prihlasen
        ///////////// PRO NEPRIHLASENE UZIVATELE ///////////////
        ?>
        <b>Přihlášení uživatele</b>
        <form action="" method="POST">
            <table>
                <tr>
                    <td>Login:</td>
                    <td><input type="text" name="login"></td>
                </tr>
                <tr>
                    <td>Heslo:</td>
                    <td><input type="password" name="heslo"></td>
                </tr>
            </table>
            <input type="hidden" name="action" value="login">
            <input type="submit" name="potvrzeni" value="Přihlásit">
        </form>

        <?php
        ///////////// KONEC: PRO NEPRIHLASENE UZIVATELE ///////////////
    }
    else { // je prihlasen

        ///////////// PRO PRIHLASENE UZIVATELE ///////////////
        ?>
        <b>Přihlášený uživatel</b><br>
        <?php
        echo "Jméno: " . $_SESSION["user"]['FULL_NAME'] . "<br>
            Login: " . $_SESSION["user"]["LOGIN"] . "<br>
            E-mail: " . $_SESSION["user"]["EMAIL"] . "<br>
            Právo: " . $_SESSION["user"]["TITLE"] . "<br>";
        ?>
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

<?php
    // paticka
    foot();
?>
             