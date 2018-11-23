<!doctype html>
<?php 
    // načtení souboru s funkcemi
    require("funkce.php");
?>
<html>
    <head>
        <meta charset="utf-8">
        <title>Výstup</title>
    </head>
    <body>
        <h1>Výstup formuláře</h1>
    
        <div><strong>Post:</strong> <br>
<?php
    // pokud je, vypíše přijaté pole do tabulky
    echo tabulka($_POST);
?>
        </div>
        <br>
        
        <div><strong>Get:</strong> <br>
<?php
    // pokud je, vypíše přijaté pole do tabulky
    echo tabulka($_GET);
?>
        </div>
        
    </body>
</html>