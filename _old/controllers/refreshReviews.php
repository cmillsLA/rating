<?php

$q=$_GET["property"];

$con = mysqli_connect('mysql1208.ixwebhosting.com','cmills8_rate','C8897163m!','cmills8_rating');
if (!$con)
  {
  die('Could not connect: ' . mysqli_error($con));
  }

mysqli_select_db($con,"cmills8_rating");

$sqlInv="SELECT * FROM reviews WHERE propertyId = '".$q."'";

$invoiceResult = mysqli_query($con,$sqlInv);

$invoices_arr = array();

while($rowInv = mysqli_fetch_array($invoiceResult)) {
	$invArr['title'] = $rowInv['title'];
	$invArr['rating'] = $rowInv['rating'];
	$invArr['review'] = $rowInv['review'];
	$invArr['userId'] = $rowInv['userId'];
	$invArr['propertyId'] = $rowInv['propertyId'];
	$invArr['date'] = $rowInv['date'];
	$invArr['years'] = $rowInv['years'];
	$invArr['months'] = $rowInv['months'];
	$invArr['facebookUrl'] = $rowInv['facebookUrl'];
	
	array_push($invoices_arr, $invArr);
}

// Get average from database
$sqlAvg="SELECT * FROM properties WHERE propertyId = '".$q."'";

$propertyResult = mysqli_query($con,$sqlAvg);

while($avg = mysqli_fetch_array($propertyResult)) {
	$avgRating = $avg['avgRating'];
}

//$invoices = array(invoices => $invoices_arr, businessData => $return_arr);

$invoices = array(userReviews => $invoices_arr, avgRating => $avgRating);

// Print JSON
echo json_encode($invoices);

mysqli_close($con);
?>