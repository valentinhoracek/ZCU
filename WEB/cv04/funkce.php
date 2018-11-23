<?php
/**
 * Created by PhpStorm.
 * User: valentin
 * Date: 23.11.2018
 * Time: 14:09
 */

/**
kom
 */
function vypis(){
    $obsah = file_get_contents("zvirata.txt");
    $radky = explode("\n", $obsah);

    return $radky;
}

function tabulka($pole){
    $vystup = "";

    if(count($pole) > 0) {
        $vystup .= "<table border=1><tr><th>Klic</th><th>Hodnota</th></tr>";

        foreach($pole as $key => $value){
            $vystup .= "<tr><td>$key</td>
						<td>";
            if(is_array($value)) {
                $vystup .= tabulka($value);
            } else {
                $vystup .= $value;
            }

            $vystup .= "</td></tr>";
        }


        $vystup .= "</table>";
    }
    else {
        $vystup .= "prazdne pole";
    }


    return $vystup;
}
