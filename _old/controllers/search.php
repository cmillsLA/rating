<?php

$address = $_POST["address"];
$address2 = $_POST["address2"];
$city = $_POST["city"];
$state = $_POST["state"];
$zip = $_POST["zip"];
$distance = $_POST["distance"];
$results = $_POST["results"];

$string = $address . $address2 . ',' . $city . ',' . $state . $zip;
$string = str_replace (" ", "+", urlencode($string));
$details_url = "http://maps.googleapis.com/maps/api/geocode/json?address=".$string."&sensor=false";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $details_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$response = json_decode(curl_exec($ch), true);

// If Status Code is ZERO_RESULTS, OVER_QUERY_LIMIT, REQUEST_DENIED or INVALID_REQUEST
if ($response['status'] != 'OK') {
 return null;
}

$geometry = $response['results'][0]['geometry'];
 
$latitude = $geometry['location']['lat'];
$longitude = $geometry['location']['lng'];

$con = mysqli_connect('mysql1208.ixwebhosting.com','cmills8_rate','C8897163m!','cmills8_rating');
if (!$con)
  {
  die('Could not connect: ' . mysqli_error($con));
  }

mysqli_select_db($con,"cmills8_rating");

// Nearby results
$sqlNearby = "SELECT *, ( 3959 * acos( cos( radians('".$latitude."') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('".$longitude."') ) + sin( radians('".$latitude."') ) * sin( radians( lat ) ) ) ) AS distance FROM properties HAVING distance < ".$distance." ORDER BY distance LIMIT 0 , ".$results.";";

$nearbyResult = mysqli_query($con,$sqlNearby);

$results_array = array();

while($rowResult = mysqli_fetch_array($nearbyResult)) {
	$resultArr['name'] = $rowResult['name'];
	$resultArr['address'] = $rowResult['address'];
	$resultArr['address2'] = $rowResult['address2'];
	$resultArr['city'] = $rowResult['city'];
	$resultArr['state'] = $rowResult['state'];
	$resultArr['zip'] = $rowResult['zip'];
	$resultArr['propertyId'] = $rowResult['propertyId'];
	$resultArr['lat'] = $rowResult['lat'];
	$resultArr['lng'] = $rowResult['lng'];
	$resultArr['website'] = $rowResult['website'];
	$resultArr['neighborhood'] = $rowResult['neighborhood'];
	
	array_push($results_array, $resultArr);
}

//$invoices = array(invoices => $invoices_arr, businessData => $return_arr);

$results = array(properties => $results_array);

// Print JSON
echo json_encode($results);

mysqli_close($con);
?>