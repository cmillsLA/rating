<?php

$q=$_GET["user"];

$con = mysqli_connect('mysql1208.ixwebhosting.com','cmills8_rate','C8897163m!','cmills8_rating');
if (!$con)
  {
  die('Could not connect: ' . mysqli_error($con));
  }

mysqli_select_db($con,"cmills8_rating");

// Client info
/*
$sql="SELECT * FROM clientInfo WHERE clientId = '".$q."'";

$result = mysqli_query($con,$sql);

$return_arr = array();

$row = mysqli_fetch_array($result);

$return_arr['clientId'] = $row['clientId'];
$return_arr['businessName'] = $row['businessName'];
$return_arr['contactName'] = $row['contactName'];
$return_arr['businessEmail'] = $row['businessEmail'];
$return_arr['businessPhone'] = $row['businessPhone'];
$return_arr['businessWebsite'] = $row['businessWebsite'];
$return_arr['businessAddress'] = $row['businessAddress'];
$return_arr['businessAddress2'] = $row['businessAddress2'];
$return_arr['businessCity'] = $row['businessCity'];
$return_arr['businessState'] = $row['businessState'];
$return_arr['businessZip'] = $row['businessZip'];
*/
// Invoice info

$sqlInv="SELECT * FROM reviews WHERE userId = '".$q."'";

$invoiceResult = mysqli_query($con,$sqlInv);

$invoices_arr = array();

while($rowInv = mysqli_fetch_array($invoiceResult)) {
	$invArr['rating'] = $rowInv['rating'];
	$invArr['review'] = $rowInv['review'];
	$invArr['propertyName'] = $rowInv['propertyName'];
	$invArr['propertyId'] = $rowInv['propertyId'];
	$invArr['date'] = $rowInv['date'];
	$invArr['months'] = $rowInv['months'];
	
	array_push($invoices_arr, $invArr);
}

//$invoices = array(invoices => $invoices_arr, businessData => $return_arr);

$invoices = array(userReviews => $invoices_arr);

// Print JSON
echo json_encode($invoices);

mysqli_close($con);
?>