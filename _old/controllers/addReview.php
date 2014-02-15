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

// Add review to reviews table
$database->insert("reviews", array(
	"title" => $_POST['title'],
  "rating" => $_POST['rating'],
  "review" => $_POST['review'],
  "userId" => $_POST['userId'],
  "propertyId" => $_POST['propertyId'],
  "date" => $_POST['date'],
  "years" => $_POST['years'],
  "months" => $_POST['months'],
  "facebookUrl" => $_POST['facebookUrl']
));

$data = $database->query("SELECT * FROM properties WHERE propertyId = " . $_POST['propertyId']. "")->fetchAll();

foreach($data as $datas) {
	$avgRating = $datas["avgRating"];
	$totalReviews = $datas["totalReviews"];
}
$newReviews = $totalReviews + 1;
$total = $avgRating * $totalReviews;
$newTotal = $total + $_POST['rating'];
$newAvg = $newTotal / $newReviews;

$database->update("properties", array(
     "avgRating" => $newAvg,
     "totalReviews" => $newReviews
), array(
    "propertyId" => $_POST['propertyId']
));


/*
echo 'test';
echo $avgRating;
echo $totalReviews;
echo '/test';

echo $newReviews;
echo $total;
echo $newTotal;
echo $newAvg;
*/
$data = array('avgRating' => $newAvg);
echo json_encode($data);
?>