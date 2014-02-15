<?php
// Independent configuration
require 'medoo.php';
$database = new medoo(array( 
  // required 
  'database_type' => 'mysql', 
  'database_name' => 'cmills8_rating', 
  'server' => 'mysql1208.ixwebhosting.com', 
  'username' => 'cmills8_rate', 
  'password' => 'C8897163m!' 
 
));

$insertId = $database->insert("properties", array(
	"name" => $_POST['name'],
  "address" => $_POST['address'],
  "address2" => $_POST['address2'],
  "city" => $_POST['city'],
  "state" => $_POST['state'],
  "zip" => $_POST['zip'],
  "lat" => $_POST['lat'],
  "lng" => $_POST['lng'],
  "website" => $_POST['website']
));

$propertyId = array(propertyId => $insertId);

echo json_encode($propertyId);

?>