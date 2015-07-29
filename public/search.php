<?php

    require(__DIR__ . "/../includes/config.php");

    // numerically indexed array of places
    $places = [];
    // search database for places matching $_GET["geo"]
    
    //states have a space and comma inbetween
    if ((strpos($_GET['geo'],' ')) !=false && (strpos($_GET['geo'],',') !=false)) {
       $plus="+". $_GET['geo'];
       $explodes=explode(", ", $_GET['geo']);
       //fullstate name
       if (strlen(end($explodes)) > 3) {
               $plus1= "+".end($explodes);
               $plus2=str_replace(end($explodes), $plus1, $plus);
               $places=query("SELECT place_name, admin_name1, postal_code, latitude, longitude  FROM places WHERE MATCH(place_name, admin_name1) AGAINST ('$plus2') ");
       }
       //state abbreviation    
       else {
        $state=end($explodes);
        $result=(count($explodes)-1);
        $town=array_slice($explodes,0, $result);
        $towns=implode($town);
        $places=query("SELECT place_name, admin_name1, postal_code, latitude, longitude FROM places WHERE (admin_code1) LIKE '$state' AND (place_name) LIKE '$towns'");

    }
    }
    
        
    //checks to see if zipcode was entered
    
    else if (ctype_digit($_GET['geo'])) {
        $places=query("SELECT place_name, admin_name1, postal_code, latitude, longitude FROM places WHERE MATCH(postal_code) AGAINST ('$_GET[geo]')");
    }
    
    
    //checks to see if + was entered
    else if (strpos($_GET['geo'],' ') !=false) {
        $plus="+".$_GET['geo'];
        $explodes=explode(" ", $_GET['geo']);
            //if full state name
            if (strlen(end($explodes)) > 2) {
            $plus1= "+".end($explodes);
            $plus2=str_replace(end($explodes), $plus1, $plus);
            $new=str_replace(' ', " +", $plus);
            $places=query("SELECT place_name, admin_name1, postal_code, latitude, longitude FROM places WHERE MATCH(place_name,admin_name1 ) AGAINST ('$plus2')");


            }
            //if state abbreviation
            else {
                $first=$explodes[0];
                $test=end($explodes);
                $places=query("SELECT place_name, admin_name1, postal_code, latitude, longitude FROM places WHERE (admin_code1) LIKE '$test' AND place_name LIKE '$first'");
            }
    }
    //town and state seperated by comma
    else if (strpos($_GET['geo'],',') !=false)  {
            $plus="+".$_GET['geo'];
            $explodes=explode(",", $_GET['geo']);
            //if full state name
            if (strlen(end($explodes)) > 2) {
            $new=str_replace(',', "  +", $plus);
            $places=query("SELECT place_name, admin_name1, postal_code, latitude, longitude FROM places WHERE MATCH(place_name,admin_name1 ) AGAINST ('$new')");
           }          
            //if state abbreviation
            else {
                $first=$explodes[0];
                $test=end($explodes);
                $places=query("SELECT place_name, admin_name1, postal_code, latitude, longitude FROM places WHERE (admin_code1) LIKE '$test' AND place_name LIKE '$first'");
            }
    }   

    // output places as JSON (pretty-printed for debugging convenience)
    header("Content-type: application/json");
    print(json_encode($places, JSON_PRETTY_PRINT));

?>
