<?php
function getCoordinates($address){

    $address = str_replace(" ", "+", $address); // replace all the white space with "+" sign to match with google search pattern

    $url = "http://maps.google.com/maps/api/geocode/json?sensor=false&address=$address";

    $response = file_get_contents($url);

    $json = json_decode($response,TRUE); //generate array object from the response from the web

    return ($json['results'][0]['geometry']['location']['lat'].",".$json['results'][0]['geometry']['location']['lng']);

}


$dom = new DOMDocument("1.0");
$node = $dom->createElement("markers");
$parnode = $dom->appendChild($node);
require_once 'DataBase.php';
$db=new DataBase();
$db->query("SELECT * FROM markers WHERE 1");
$db->execute();
$arr = $db->resultset();
header("Content-type: text/xml");
foreach ($arr as $row){
    $node = $dom->createElement("marker");
    $newnode = $parnode->appendChild($node);
    $newnode->setAttribute("name",$row['name']);
    $newnode->setAttribute("address", $row['address']);
    $c=getCoordinates($row['address']);
    $coord=explode(',',$c);
    $newnode->setAttribute("lat",$coord[0]);
    $newnode->setAttribute("lng", $coord[1]);
    $newnode->setAttribute("type", $row['type']);
}
echo $dom->saveXML();
