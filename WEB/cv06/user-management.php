<?php 
    // nacteni hlavicky stranky
    include("zaklad.php");
    head("Správa uživatelů");
?>


<?php 
    // načtení souboru s funkcemi
    include("database.class.php");
    $PDOObj = new Database();

?>

<?php

    if (!$PDOObj->isUserLoged()) {
    ///////////// PRO NEPRIHLASENE UZIVATELE ///////////////
        ?>
        <b>Tato strána je dostupná pouze přihlášeným uživatelům.</b>
        <?php
    ///////////// KONEC: PRO NEPRIHLASENE UZIVATELE ///////////////
    }
    else {// je prihlasen
        if ($_SESSION["user"]["ID_RIGHTS"] != 1) {

        ///////////// PRO PRIHLASENE UZIVATELE - NENI ADMIN ///////////////
        ?>
        <b>Správu uživatelů mohou provádět pouze uživatelé s právem Administrátor.</b>
        <?php
        ///////////// KONEC: PRO PRIHLASENE UZIVATELE - NENI ADMIN ///////////////
        }
        else {
            ///////////// PRO PRIHLASENE UZIVATELE - JE ADMIN ///////////////
            if (isset($_POST["potvrzeni"]) && isset($_POST["user-id"])) {
                if ($_POST["user-id"] != "") {
                    $res = $PDOObj->deleteUser($_POST["user-id"]);
                    if ($res) {
                        echo "<b>Uživatel s ID:" . $_POST["user-id"] . " byl smazán.</b><br><br>";
                    } else {
                        echo "<b>Uživatele s ID:" . $_POST["user-id"] . " se nepodařilo smazat!</b><br><br>";
                    }
                } else {
                    echo "<b>Neznámé ID uživatele. Mazání nebylo provedeno!</b><br><br>";
                }
            }

            // zpracovani odeslanych formularu

            ?>
            <b>Seznam uživatelů</b>
            <table border="1">
                <tr>
                    <th>ID</th>
                    <th>Login</th>
                    <th>Jméno</th>
                    <th>E-mail</th>
                    <th>Právo</th>
                    <th>Akce</th>
                </tr>
                <?php
                $users = $PDOObj->allUsersInfo(); // vsichni uzivatele
                foreach ($users as $u) {
                    if ($u["ID"] != $_SESSION["user"]["ID"]) { // aktualni uzivatele nevypisuju
                        echo "<tr><td>$u[ID]</td><td>$u[LOGIN]</td><td>$u[FULL_NAME]</td><td>$u[EMAIL]</td><td>$u[TITLE]</td>
                                <td>
                                    <form action='' method='POST'>
                                        <input type='hidden' name='user-id' value='$u[ID]'>
                                        <input type='submit' name='potvrzeni' value='Smazat'>
                                    </form>
                                </td>
                              </tr>";
                    }
                }
                ?>
            </table>
            <?php
            /* // akce by mela obsahovat formular s tlacitkem:
                <form action="" method="POST">
                    <input type="hidden" name="user-id" value="....">
                    <input type="submit" name="potvrzeni" value="Smazat">
                </form>

            */
            ///////////// KONEC: PRO PRIHLASENE UZIVATELE - JE ADMIN ///////////////
        }
    }
?>

<?php
    // paticka
    foot();
?>
             