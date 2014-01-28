<?php

/**
 * Parser to create json return used by the Penn State Mirror App.  
 * 
 * MySQL and SQLite3 have been tested for the database and can be chosen below. If
 * SQLite3 is used you should make sure the database is not downloadable by the web
 * server. MySQL can also use SSL encryption. Uncomment the method you would like to 
 * use.
 */

/** SQLite3 database option */
//$db = new PDO('sqlite:appletvs.db');

/** MySQL database option w/o SSL */
//$db = new PDO('mysql:host=hostname;dbname=appletvs','username','password');

/** MySQL database option with SSL */
//$db = new PDO(
//    'mysql:host=hostname;dbname=ssldb',
//    'username',
//    'password',
//    array(
//        PDO::MYSQL_ATTR_SSL_KEY    =>'/path/to/client-key.pem',
//        PDO::MYSQL_ATTR_SSL_CERT=>'/path/to/client-cert.pem',
//        PDO::MYSQL_ATTR_SSL_CA    =>'/path/to/ca-cert.pem'
//    )
//);

/** Retrieve url parameters for JSON call */
$request = $_GET['response'];
$request_id = intval($_GET['id']);

/** Select correct JSON database query from request */
if ($request == "campuses") { 
     $query = "SELECT id,name FROM campuses ORDER BY weight,name";
   }
elseif ($request == "buildings") {
     $query = "SELECT id,name FROM buildings WHERE campus_id='" . $request_id . "' ORDER BY name";
   }
elseif ($request == "rooms") {
     $query = "SELECT id,name FROM rooms WHERE building_id='" . $request_id . "' ORDER BY name";
   }
elseif ($request == "devices") {
     $query = "SELECT id,name,model,host FROM devices WHERE room_id='" . $request_id . "' ORDER BY name";
   }
// Return nothing if doesn't match a request 
else { exit; }

/** Run query and build JSON response from result */  
$json_arr = array();
$json_arr = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
$json_string = json_encode($json_arr);

header('Content-Type: application/json');
echo $json_string;
exit;
?>
