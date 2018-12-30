<?php 
    // nacteni hlavicky stranky
    include("zaklad.php");
    head("Úprava osobních údajů uživatele");
?>

<?php 
    // načtení souboru s funkcemi
    include("database.class.php");
    $PDOObj = new Database();
?>

<?php
   // zpracovani odeslanych formularu
    if (isset($_POST['potvrzeni'])) {
        if ($PDOObj->isPasswordCorrect($_SESSION["user"]["login"], $_POST["heslo-puvodni"])) {
            if ($_POST["heslo"] == $_POST["heslo2"]) {
                if($_POST["heslo"] == "") {
                    $heslo = $_SESSION["user"]["heslo"];
                }
                else {
                    $heslo = $_POST["heslo"];
                }

                $PDOObj->updateUserInfo($_SESSION["user"]["ID"], $_POST["jmeno"], $heslo, $_POST["email"], $_POST["pravo"]);

                $PDOObj->userLogin($_SESSION["user"]["LOGIN"], $heslo);
                echo "<b>Osobni udaje byly zmenenny.</b><br><br>";
            }
            else {
                echo "<b>Vami zadana hesla nejsou stejna</b><br><br>";
            }
        }
        else {
            echo "<b>Vami zadane soucasne heslo neni spravne</b><br><br>";
        }
    }


    if (!$PDOObj->isUserLoged()) {
        ///////////// PRO NEPRIHLASENE UZIVATELE ///////////////
        ?>
        <b>Osobní údaje mohou měnit pouze přihlášení uživatelé.</b>

        <?php
        ///////////// KONEC: PRO NEPRIHLASENE UZIVATELE ///////////////
    }
    else {
        ///////////// PRO PRIHLASENE UZIVATELE ///////////////
        ?>
        <b>Osobní údaje</b>
        <form action="" method="POST" oninput="x.value=(pas1.value==pas2.value)?'OK':'Nestejná hesla'">
            <table>
                <tr>
                    <td>Současné heslo:</td>
                    <td><input type="password" name="heslo-puvodni" required></td>
                </tr>
                <tr>
                    <td>Login:</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Heslo 1:</td>
                    <td><input type="password" name="heslo" id="pas1"></td>
                </tr>
                <tr>
                    <td>Heslo 2:</td>
                    <td><input type="password" name="heslo2" id="pas2"></td>
                </tr>
                <tr>
                    <td>Ověření hesla:</td>
                    <td>
                        <output name="x" for="pas1 pas2"></output>
                    </td>
                </tr>
                <tr>
                    <td>Jméno:</td>
                    <td><input type="text" name="jmeno" required></td>
                </tr>
                <tr>
                    <td>E-mail:</td>
                    <td><input type="email" name="email" required></td>
                </tr>
                <tr>
                    <td>Právo:</td>
                    <td>
                        <select name="pravo">
                            <option value=""></option>
                        </select>
                    </td>
                </tr>
            </table>

            <input type="submit" name="potvrzeni" value="Upravit osobní údaje">
        </form>

        <?php
        ///////////// KONEC: PRO PRIHLASENE UZIVATELE ///////////////
    }
?>

<?php
    // paticka
    foot();
?>
             