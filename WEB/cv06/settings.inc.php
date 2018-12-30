<?php
////////////// Soubor obsahujici zakladni nastaveni /////////////
/*
// databaze
    define("DB_SERVER","localhost");
    define("DB_NAME","web_cv06");
    define("DB_USER","root");
    define("DB_PASS","");


// stranky webu (ostatni nebudou dostupne)
    $phpExtension = ".php"; // pripona

    define("WEB_PAGES", [
        'log' => "login".$phpExtension,
        'reg' => "user-registration".$phpExtension,
        'upd' => "user-update".$phpExtension,
        'mng' => "user-management".$phpExtension
    ]);

    define("WEB_PAGE_DEFAULT_KEY", 'log');
*/

global $db_server, $db_name, $db_user, $db_pass;
global $web_pagesExtension, $web_pages;

// databaze
/*
$db_server = "students.kiv.zcu.cz";
$db_name = "db1_vyuka";
$db_user = "db1_vyuka";
$db_pass = "db1_vyuka";
*/

$db_server = "localhost";
$db_name = "web_cv06";
$db_user = "root";
$db_pass = "";

// stranky webu (ostatni nebudou dostupne)
$web_pagesExtension = ".php";
$web_pages[0] = "login";
$web_pages[1] = "user-registration";
$web_pages[2] = "user-update";
$web_pages[3] = "user-management";
?>